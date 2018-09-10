<?php namespace TLE;

use Carbon\Carbon;
use Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Telegram;
use Telegram\Bot\FileUpload\InputFile;

class TLESender
{
    /**
     *
     * ERROR MESSAGE
     *
     * @var STRING
     *
     */
    private $error = null;
    /**
     *
     * MESSAGE
     *
     * @var STRING
     *
     */
    private $message = '';
    /**
     *
     * LOG NAME
     *
     * @var STRING
     *
     */
    private $log_name = '';
    /**
     *
     * PREPARE SHORT ERROR AND FILE
     *
     * @param STRING $error
     *
     * @return VOID
     *
     */
    private function prepare($error)
    {

        ##
        $this->error = $error;

        if (Config::get('tle.save_log')) {

            Log::critical($this->error);

        }
        ##

        $this->message .= trans('tle::tlemessage.project') . env('APP_NAME') . "\n";

        $this->message .= trans('tle::tlemessage.error') . $this->error->getMessage() . "\n";

        $this->message .= trans('tle::tlemessage.date_time') . Carbon::now()->format("Y.d.m H:i:s") . "\n";

        ##
        # LOG SAVE
        #
        $this->log_name = 'project_' . env('APP_NAME') . '_' . time() . '.log';

        Storage::disk(

            Config::get('tle.path_save')

        )->put(

            $this->log_name,

            $this->error

        );

    }
    /**
     *
     * SEND LOG
     *
     * @param STRING  $error
     *
     * @return VOID
     *
     */
    public function send($error)
    {

        #
        $this->prepare($error);
        #

        Telegram::sendDocument([

            'chat_id'    => Config::get('tle.chat_id'),

            'parse_mode' => 'html',

            'document'   => InputFile::create(

                Storage::disk('local')->path(

                    $this->log_name

                )

            ),

            'caption'    => $this->message,

        ]);

        ##
        # DELETE LOG
        #
        Storage::disk(

            Config::get('tle.path_save')

        )->delete(

            $this->log_name

        );

    }

}
