<?php namespace TLE;

/**
 *
 * Class TLESender
 *
 * @package TLE
 *
 * @license MIT
 *
 */

use Carbon\Carbon;
use Config;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Telegram;
use Telegram\Bot\FileUpload\InputFile;
use TLE\Exceptions\StringsErrors;

class TLESender
{
    /**
     *
     * EXCEPTION ERROR
     *
     * @var EXCEPTION
     *
     */
    private $error = null;
    /**
     *
     * ADDITIONAL INFORMATION
     *
     * @var STRING
     *
     */
    private $addinfo = null;
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
     * @return VOID
     *
     */
    private function prepare()
    {

        $error_message = '';

        $data_file = $this->error . "\n" . $this->addinfo;

        if ($this->error) {

            if (strlen($this->error->getMessage()) > 100) {

                $error_message .= "\n" . trans('tle::tlemessage.error') . Str::limit(

                    $this->error->getMessage(), Config::get('tle.limit_error_message')

                );

            } else {

                $error_message .= "\n" . trans('tle::tlemessage.error') . $this->error->getMessage();

            }

        }

        if ($this->addinfo) {

            if (strlen($this->addinfo) > 100) {

                $error_message .= "\n" . trans('tle::tlemessage.extras_information') . Str::limit(

                    $this->addinfo, Config::get('tle.limit_error_message')

                );

            } else {

                $error_message .= "\n" . trans('tle::tlemessage.extras_information') . $this->addinfo;

            }

        }

        ##

        $this->message .= trans('tle::tlemessage.project') . env('APP_NAME');

        $this->message .= $error_message . "\n";

        $this->message .= trans('tle::tlemessage.date_time') . Carbon::now()->format("Y.d.m H:i:s") . "\n";

        ##
        # CHECK LENGTH MESSAGE
        #

        if (strlen($this->message) > 263) {

            throw new StringsErrors('Message max length. Max 263 length');

        } else {

            ##
            # LOG SAVE
            #
            $this->log_name = env('APP_NAME') . '_' . time() . '.log';

            Storage::disk(

                Config::get('tle.path_save')

            )->put(

                $this->log_name,

                $data_file

            );

            ##
            # SAVE ERROR IN APP
            #
            if (Config::get('tle.save_log')) {

                \Illuminate\Support\Facades\Log::critical(

                    $data_file

                );

            }

        }

    }
    /**
     *
     * EXCEPTION
     *
     * @param EXCEPTION
     *
     * @return OBJECT
     *
     */
    public function exp(\Exception $error)
    {

        if (method_exists($error, 'getMessage')) {

            $this->error = $error;

        }

        return $this;

    }
    /**
     *
     * GUZZLE EXCEPTION
     *
     * @param RequestException $error
     *
     * @return OBJECT
     *
     */
    public function guzzle(RequestException $error)
    {

        $this->error = $error->getResponse()->getBody();

        return $this;

    }
    /**
     *
     * ADDITIONAL INFORMATION
     *
     * @param STRING | ARRAY $addinfo
     *
     * @return OBJECT
     *
     */
    public function info(String $addinfo)
    {

        if (strlen($addinfo) < 200) {

            $this->addinfo = $addinfo;

            return $this;

        }

        throw new StringsErrors('Info max long. Max 200 length');

    }
    /**
     *
     * SEND LOG
     *
     * @param EXCEPTION $error
     *
     * @param STRING $info
     *
     * @return VOID
     *
     */
    public function send()
    {

        ##
        # CHECK FIELD BOT NAME AND CHAT_ID
        #
        if (!Config::get('tle.botname') || !Config::get('tle.chat_id')) {

            throw new \TLE\Exceptions\ConfigErrors('Empty field botname or chat_id');

        }
        ##
        # CHECK FIELD DATA
        #
        if ($this->error == null && $this->addinfo == null) {

            throw new StringsErrors('Empty string $error or $addinfo');

        }

        #
        $this->prepare();
        #

        try {

            Telegram::bot(

                Config::get('tle.botname')

            )->sendDocument([

                'chat_id'    => Config::get('tle.chat_id'),

                'parse_mode' => 'html',

                'document'   => InputFile::create(

                    Storage::disk('local')->path(

                        $this->log_name

                    )

                ),

                'caption'    => $this->message,

            ]);

        } catch (\Telegram\Bot\Exceptions\TelegramResponseException $error) {

            throw new Exception($error);

        }

        ##
        # DELETE LOG
        #
        Storage::disk(

            Config::get('tle.path_save')

        )->delete(

            $this->log_name

        );
        ##
        # CLEAR
        #
        unset(

            $this->message,

            $this->log_name,

            $this->error,

            $this->addinfo

        );
    }

}
