<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class chat_message_attach extends Model
{
    use LogsActivity;

    protected $table = 'chat_message_attach';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'chat_message_id',
        'mime',
        'name',
        'original_name'
    ];

    protected static $logAttributes = [
        'uuid',
        'chat_message_id',
        'mime',
        'name',
        'original_name'
    ];

    public function hasMessage()
    {
        return $this->belongsTo('App\chat_message', 'chat_message_id');
    }

    public function getMimeIcon()
    {
        // List of official MIME Types: http://www.iana.org/assignments/media-types/media-types.xhtml
        $icon_classes = array(
            // Media
            'image' => 'fa-file-image-o',
            'audio' => 'fa-file-audio-o',
            'video' => 'fa-file-video-o',
            // Documents
            'application/pdf' => 'fa-file-pdf-o',
            'application/msword' => 'fa-file-word-o',
            'application/vnd.ms-word' => 'fa-file-word-o',
            'application/vnd.oasis.opendocument.text' => 'fa-file-word-o',
            'application/vnd.openxmlformats-officedocument.wordprocessingml' => 'fa-file-word-o',
            'application/vnd.ms-excel' => 'fa-file-excel-o',
            'application/vnd.openxmlformats-officedocument.spreadsheetml' => 'fa-file-excel-o',
            'application/vnd.oasis.opendocument.spreadsheet' => 'fa-file-excel-o',
            'application/vnd.ms-powerpoint' => 'fa-file-powerpoint-o',
            'application/vnd.openxmlformats-officedocument.presentationml' => 'fa-file-powerpoint-o',
            'application/vnd.oasis.opendocument.presentation' => 'fa-file-powerpoint-o',
            'text/plain' => 'fa-file-text-o',
            'text/html' => 'fa-file-code-o',
            'application/json' => 'fa-file-code-o',
            // Archives
            'application/gzip' => 'fa-file-archive-o',
            'application/zip' => 'fa-file-archive-o',
        );
        foreach ($icon_classes as $text => $icon) {
            if (strpos($this->mime, $text) === 0) {
                return $icon;
            }
        }
        return 'fa-file-o';
    }
}
