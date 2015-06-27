<?php

namespace Modus\Response;

abstract class ContentTypes
{
    const ALL = '*/*';

    const APPLICATION_JSON = 'application/json';
    const APPLICATION_XML = 'appplication/xml';
    const APPLICATION_PDF = 'application/pdf';

    const IMAGE_GIF = 'image/gif';
    const IMAGE_PNG = 'image/png';
    const IMAGE_PJPEG = 'image/pjpeg';
    const IMAGE_BMP = 'image/bmp';
    const IMAGE_SVGXML = 'image/svg+xml';
    const IMAGE_TIFF = 'image/tiff';

    const TEXT_CSS = 'text/css';
    const TEXT_CSV = 'text/csv';
    const TEXT_HTML = 'text/html';
    const TEXT_MARKDOWN = 'text/markdown';
    const TEXT_JAVASCRIPT = 'text/javascript';
    const TEXT_PLAIN = 'text/plain';
    const TEXT_RTF = 'text/rtf';
    const TEXT_VCARD = 'text/vcard';
    const TEXT_XML = 'text/xml';

    const VIDEO_AVI = 'video/avi';
    const VIDEO_MPEG = 'video/mpeg';
    const VIDEO_MP4 = 'video/mp4';
    const VIDEO_OGG = 'video/ogg';
    const VIDEO_QUICKTIME = 'video/qicktime';
    const VIDEO_WEBM = 'video/webm';
    const VIDEO_MATROSKA = 'video/matroska';
    const VIDEO_WINDOWS = 'video/x-ms-wmv';
    const VIDEO_FLASH = 'video/x-flv';
}
