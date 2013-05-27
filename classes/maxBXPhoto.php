<?php
class maxBXPhoto
{
    const URI_TPL_SOURCE      = 'http://maxposter.ru/photo/%d/%d/orig/%s';
    const URI_TPL_DESTINATION = '/upload/%s/%d/%d/%s/%s';

    static private $prepDestination;

    static private function getDestinationPath()
    {
        if (!static::$prepDestination) {
            static::$prepDestination = static::URI_TPL_DESTINATION . rtrim($_SERVER["DOCUMENT_ROOT"], '/');
        }

        return static::$prepDestination;
    }


    /**
     * @param $arSizes
     * @return bool
     */
    static public function resize($arSizes)
    {
        return true;
    }


    /**
     * ѕуть к фото
     *
     * @param  string  $fileName
     * @param  mixed   $arSize
     * @return string
     */
    static public function getPath($fileName, $arSize = 'original')
    {
        return false;
    }


    static public function getUri($fileName, $arSize = 'original')
    {
        return '';
    }
}
