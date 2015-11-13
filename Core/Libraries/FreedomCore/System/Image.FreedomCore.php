<?php

Class Image extends FreedomCore
{
    private static $Image;
    private static $Width;
    private static $Height;
    private static $ImageResized;

    public static function Initialize($FileName)
    {
        Image::$Image = Image::openImage($FileName);
        Image::$Width = imagesx(Image::$Image);
        Image::$Height = imagesy(Image::$Image);
    }

    public static function CreateSlideShowImage($FileName)
    {
        $Extension = strrchr($FileName, '.');
        $SlideShowPath = getcwd().DS."Uploads".DS."Core".DS."Slideshow".DS;
        $NewImageName = strtoupper(md5(uniqid(rand(), true))).$Extension;
        $SavePath = $SlideShowPath.$NewImageName;
        Image::Initialize($FileName);
        Image::Resize(640, 300, "exact");
        Image::saveImage($SavePath);
        Image::CreateNewsImage($FileName, $NewImageName);
        unlink($FileName);
        return ['filePath' => $SavePath, 'name' => $NewImageName, 'url' => '//'.$_SERVER['SERVER_NAME'].'/Uploads/Core/Slideshow/'.$NewImageName];
    }

    public static function CreateNewsImage($FileName, $NewImageName)
    {
        $Extension = strrchr($FileName, '.');
        $SlideShowPath = getcwd().DS."Uploads".DS."Core".DS."News".DS;
        $SavePath = $SlideShowPath.$NewImageName;
        Image::Resize(622, 240, "exact");
        Image::saveImage($SavePath);
    }

    public static function MoveUploadedImage($FilePath, $FileName)
    {
        $TargetPath = getcwd().DS."Uploads".DS."Core".DS."TMPUploads".DS;
        $ImagePath = $TargetPath.$FileName;
        move_uploaded_file($FilePath,$ImagePath);
        return $ImagePath;
    }

    private static function openImage($FileName)
    {
        $Extension = strtolower(strrchr($FileName, '.'));

        switch($Extension)
        {
            case '.jpg':
            case '.jpeg':
                $Image = @imagecreatefromjpeg($FileName);
            break;
            case '.gif':
                $Image = @imagecreatefromgif($FileName);
            break;
            case '.png':
                $Image = @imagecreatefrompng($FileName);
            break;
            default:
                $Image = false;
            break;
        }

        return $Image;
    }

    public static function Resize($NewWidth, $NewHeight, $Option = 'auto')
    {
        $optionArray = Image::getDimensions($NewWidth, $NewHeight, strtolower($Option));

        $optimalWidth  = $optionArray['optimalWidth'];
        $optimalHeight = $optionArray['optimalHeight'];

        Image::$ImageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
        imagealphablending(Image::$ImageResized, false);
        imagesavealpha(Image::$ImageResized, true);
        imagecopyresampled(Image::$ImageResized , Image::$Image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, Image::$Width, Image::$Height);

        if ($Option == 'crop')
            Image::Crop($optimalWidth, $optimalHeight, $NewWidth, $NewHeight);
    }

    private static function getDimensions($newWidth, $newHeight, $option)
    {
        switch ($option)
        {
            case 'exact':
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
                break;
            case 'portrait':
                $optimalWidth = Image::getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
                break;
            case 'landscape':
                $optimalWidth = $newWidth;
                $optimalHeight= Image::getSizeByFixedWidth($newWidth);
                break;
            case 'auto':
                $optionArray = Image::getSizeByAuto($newWidth, $newHeight);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
            case 'crop':
                $optionArray = Image::getOptimalCrop($newWidth, $newHeight);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
            break;
        }
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    private static function getSizeByFixedHeight($newHeight)
    {
        $ratio = Image::$Width / Image::$Height;
        $newWidth = $newHeight * $ratio;
        return $newWidth;
    }

    private static function getSizeByFixedWidth($newWidth)
    {
        $ratio = Image::$Height / Image::$Width;
        $newHeight = $newWidth * $ratio;
        return $newHeight;
    }

    private static function getSizeByAuto($newWidth, $newHeight)
    {
        if (Image::$Height < Image::$Width)
        {
            $optimalWidth = $newWidth;
            $optimalHeight= Image::getSizeByFixedWidth($newWidth);
        }
        elseif (Image::$Height > Image::$Width)
        {
            $optimalWidth = Image::getSizeByFixedHeight($newHeight);
            $optimalHeight= $newHeight;
        }
        else
        {
            if ($newHeight < $newWidth) {
                $optimalWidth = $newWidth;
                $optimalHeight= Image::getSizeByFixedWidth($newWidth);
            } else if ($newHeight > $newWidth) {
                $optimalWidth = Image::getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
            } else {
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
            }
        }

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    private static function getOptimalCrop($newWidth, $newHeight)
    {

        $heightRatio = Image::$Height / $newHeight;
        $widthRatio  = Image::$Width /  $newWidth;

        if ($heightRatio < $widthRatio) {
            $optimalRatio = $heightRatio;
        } else {
            $optimalRatio = $widthRatio;
        }

        $optimalHeight = Image::$Height / $optimalRatio;
        $optimalWidth  = Image::$Width  / $optimalRatio;

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    private static function Crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
    {
        $cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
        $cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );

        $crop = Image::$ImageResized;
        Image::$ImageResized = imagecreatetruecolor($newWidth , $newHeight);
        imagecopyresampled(Image::$ImageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
    }

    public static function saveImage($savePath, $imageQuality="100")
    {
        $extension = strrchr($savePath, '.');
        $extension = strtolower($extension);

        switch($extension)
        {
            case '.jpg':
            case '.jpeg':
                if (imagetypes() & IMG_JPG)
                    imagejpeg(Image::$ImageResized, $savePath, $imageQuality);
                break;

            case '.gif':
                if (imagetypes() & IMG_GIF)
                    imagegif(Image::$ImageResized, $savePath);
                break;

            case '.png':
                $scaleQuality = round(($imageQuality/100) * 9);
                $invertScaleQuality = 9 - $scaleQuality;
                if (imagetypes() & IMG_PNG)
                    imagepng(Image::$ImageResized, $savePath, $invertScaleQuality);
            break;
            default:
                // *** No extension - No save.
            break;
        }

        imagedestroy(Image::$ImageResized);
    }
}

?>