<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 06.07.2014
 * Time: 12:31
 */
class AutoLoader
{
    const CLASS_MAP_FILE =  'classmap.php';
    const TMP_DIR = 'tmp';
    const CLASS_MAP_BEGIN_PART = 'begin-classmap';
    const CLASS_MAP_END_PART = 'end-classmap';
    const PATTERN_FOR_CLASS_MAP = '/^[A-Z]+[A-Za-z0-9_]*(\.php)$/';
    const SCIPPED_DOUBLE_DOT = '..';
    const SCIPPED_DOT = '.';

    protected $name;
    protected $dirs = [];
    private $classMapFile = '';

    public function __construct($name = null)
    {
        $this->name = $name;
        $this->classMapFile = self::TMP_DIR . DIRECTORY_SEPARATOR . self::CLASS_MAP_FILE;
        $this->scippedDirs = [
            self::SCIPPED_DOT,
            self::SCIPPED_DOUBLE_DOT
        ];
    }

    public static function autoLoad($name)
    {
        $loader = new self($name);
        $loader->load();
    }

    public function load()
    {
        if (!$this->name) {
            return false;
        }
        $this->loadFromMap();
    }

    public function generateMap()
    {
        $fopen = fopen($this->classMapFile, 'w+');
        fputs($fopen, $this->getContent(self::CLASS_MAP_BEGIN_PART));
        $filesContent = '';
        $files = $this->getFiles(dirname(__FILE__));
        foreach ($files as $class => $path) {
            $filesContent .= sprintf('\'%s\' => \'%s\',', $class, $path);
        }
        fputs($fopen, $filesContent . $this->getContent(self::CLASS_MAP_END_PART));
        fclose($fopen);
    }

    protected function loadFromMap()
    {
        if (file_exists($this->classMapFile)){
            $classMap = include($this->classMapFile);
            if (in_array($this->name, array_keys($classMap))) {
                require_once($classMap[$this->name]);
                return true;
            }
        }
        return false;
    }

    private function getContent($part)
    {
        switch ($part) {
            case self::CLASS_MAP_BEGIN_PART:
                $content = '<?php return [';
                break;
            case self::CLASS_MAP_END_PART:
                $content = '];';
                break;
            default:
                $content = '';
                break;
        }
        return $content;
    }

    private function getFiles($dirName)
    {
        $files = [];
        foreach (scandir($dirName) as $file) {
            if (in_array($file, $this->scippedDirs)) {
                continue;
            }
            if (preg_match(self::PATTERN_FOR_CLASS_MAP, $file)) {
                $separator = DIRECTORY_SEPARATOR;
                $files[str_replace('.php', '', $file)] = "$dirName$separator$file";
            }
            if (is_dir($file)) {
                $files = array_merge($files, $this->getFiles($file));
            }
        }
        return $files;
    }
}
