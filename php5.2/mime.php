<?php

/**
 * -----------------------------------------------------------------------------
 *              C O M M O N  L I B R A R I E S  ( P H P  5 . 2 )
 * -----------------------------------------------------------------------------
 * 
 * @package    Common
 * @author     Kijin Sung <kijin@kijinsung.com>
 * @copyright  (c) 2012, Kijin Sung <kijin@kijinsung.com>
 * @license    GPL v3 <http://www.opensource.org/licenses/gpl-3.0.html>
 * @link       http://github.com/kijin/common
 * @version    201207.1
 * 
 * -----------------------------------------------------------------------------
 * 
 * Copyright (c) 2012, Kijin Sung <kijin@kijinsung.com>
 * 
 * This program is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option)
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * -----------------------------------------------------------------------------
 */

class MIME
{
    // Method to find the MIME type for a given extension.
    
    public static function from_extension($ext)
	{
        $ext = strtolower($ext);
        return array_key_exists($ext, self::$_types) ? self::$_types[$ext] : self::$_default;
	}
    
    // Method to find the MIME type for a given filename.
    
    public static function from_filename($filename)
	{
		$ext_location = strrpos($filename, '.');
        if ($ext_location === false) return self::$_default;
        
		$ext = strtolower(substr($filename, $ext_location + 1));
        return array_key_exists($ext, self::$_types) ? self::$_types[$ext] : self::$_default;
	}
    
    // Method to find the usual extension given to an MIME type.
    
    public static function get_extension($type)
    {
        foreach (self::$_types as $ext => $mime)
        {
            if (!strncasecmp($type, $mime, strlen($type))) return $ext;
        }
        return null;
    }
    
    // The default MIME type for unknown extensions.
    
    protected static $_default = 'application/octet-stream';
    
    // List of known MIME types.
    
    protected static $_types = array(
        
        // Text.
        
        'html' => 'text/html',
        'htm' => 'text/html',
        'shtml' => 'text/html',
        'txt' => 'text/plain',
        'text' => 'text/plain',
        'log' => 'text/plain',
        'rtx' => 'text/richtext',
        'rtf' => 'text/rtf',
        'xml' => 'text/xml',
        'xsl' => 'text/xml',
        'css' => 'text/css',
        'csv' => 'text/csv',
        
        // Images.

        'bmp' => 'image/bmp',
        'gif' => 'image/gif',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpe' => 'image/jpeg',
        'png' => 'image/png',
        'svg' => 'image/svg+xml',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'ico' => 'image/vnd.microsoft.icon',
        
        // Documents.
        
        'doc' => 'application/msword',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'docs' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        'odp' => 'application/vnd.oasis.opendocument.presentation',
        'odg' => 'application/vnd.oasis.opendocument.graphics',
        'odb' => 'application/vnd.oasis.opendocument.database',
        'pdf' => 'application/pdf',
        
        // Audio.

        'mid' => 'audio/midi',
        'midi' => 'audio/midi',
        'mpga' => 'audio/mpeg',
        'mp2' => 'audio/mpeg',
        'mp3' => 'audio/mpeg',
        'mp4' => 'audio/mpeg',
        'aif' => 'audio/x-aiff',
        'aiff' => 'audio/x-aiff',
        'ra' => 'audio/x-realaudio',
        'wav' => 'audio/x-wav',
        'ogg' => 'audio/ogg',
        
        // Video.

        'avi' => 'video/x-msvideo',
        'flv' => 'video/x-flv',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'mpe' => 'video/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',
        'movie' => 'video/x-sgi-movie',
        'rv' => 'video/vnd.rn-realvideo',
        'dvi' => 'application/x-dvi',
        
        // Specialty.
        
        'psd' => 'application/x-photoshop',
        'swf' => 'application/x-shockwave-flash',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',
        'mif' => 'application/vnd.mif',
        'xul' => 'application/vnd.mozilla.xul+xml',
        
        // Code.
        
        'phps' => 'application/x-httpd-php-source',
        'js' => 'application/x-javascript',
        
        // Archives.
        
        'bz2' => 'application/x-bzip',
        'gz' => 'application/x-gzip',
        'tar' => 'application/x-tar',
        'tgz' => 'application/x-tar',
        'gtar' => 'application/x-gtar',
        'rar' => 'application/x-rar-compressed',
        'zip' => 'application/x-zip',
        
        // RFC822.
        
        'eml' => 'message/rfc822',
    );
}
