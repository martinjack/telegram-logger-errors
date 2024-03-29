<?php

namespace TLE;

use Carbon\Carbon;
use Config;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Telegram;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\FileUpload\InputFile;
use TLE\Exceptions\ConfigErrors;
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
     * LIMIT LENGTH MESSAGE
     *
     * @var INTEGER
     *
     */
    private $limit_length_message = 0;
    /**
     *
     * APP NAME
     *
     * @var STRING
     *
     */
    private $appName = null;
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
        $data_file     = sprintf(

            "%s\n%s",

            $this->error,

            $this->addinfo

        );

        ##
        # SAVE ERROR IN APP
        #
        if (Config::get('tle.save_log')) {

            Log::critical(

                $data_file

            );

        }
        ##
        # PREPARE ERROR
        #
        if ($this->error) {

            if (strlen($this->error) > 100) {

                $error_message .= sprintf(

                    "\n%s%s",

                    trans('tle::tlemessage.error'),

                    Str::limit(

                        $this->error, $this->limit_length_message

                    )

                );

            } else {

                $error_message .= sprintf(

                    "\n%s%s",

                    trans('tle::tlemessage.error'),

                    $this->error

                );

            }

        }
        ##
        # PREPARE ADD INFO
        #
        if ($this->addinfo) {

            if ($this->error || strlen($this->addinfo) > 100) {

                $error_message .= sprintf(

                    "\n%s%s",

                    trans('tle::tlemessage.extras_information'),

                    Str::limit(

                        $this->addinfo, $this->limit_length_message

                    )

                );

            } else {

                $error_message .= sprintf(

                    "\n%s%s",

                    trans('tle::tlemessage.extras_information'),

                    $this->addinfo

                );

            }

        }

        ##
        # NAME PROJECT
        #
        $this->message .= $this->getAppName();
        ##

        $this->message .= sprintf(

            "%s\n",

            $error_message

        );

        $this->message .= sprintf(

            '%s%s',

            trans('tle::tlemessage.date_time'),

            Carbon::now()->format("d.m.y H:i")

        );

        ##
        # CHECK LENGTH MESSAGE
        #
        if (strlen($this->message) > 1024) {
            ##
            # Limit message
            #
            $this->message = Str::limit($this->message, 1000);

            $this->prepare();

        } else {

            ##
            # LOG SAVE
            #
            $this->log_name = sprintf(

                '%s_%s%s',

                $this->appName,

                time(),

                '.log'

            );

            Storage::disk(

                Config::get('tle.path_save')

            )->put(

                $this->log_name,

                $data_file

            );

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
    public function exp(Exception $error)
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

        if ($error->hasResponse()) {

            $this->error = (string) $error->getResponse()->getBody();

        } else {

            $this->error = (string) $error->getMessage();

        }

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

        if (strlen($addinfo) < 101) {

            $this->addinfo = $addinfo;

            return $this;

        }

        throw new StringsErrors('Info max long. Max 101 length');

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
        # DEBUG MODE
        #
        if (Config::get('tle.debug')) {

            return false;

        }
        ##
        # CHECK FIELD BOT NAME AND CHAT_ID
        #
        if (!Config::get('tle.botname') || !Config::get('tle.chat_id')) {

            throw new ConfigErrors(

                trans('tle::tlemessage.empty_field_b_chat')

            );

        }
        ##
        # CHECK FIELD DATA
        #
        if ($this->error == null && $this->addinfo == null) {

            throw new StringsErrors(

                trans('tle::tlemessage.empty_string_e_add')

            );

        }

        #
        $this->limit_length_message = Config::get('tle.limit_error_message');
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

        } catch (TelegramResponseException $error) {

            if (Config::get('tle.disable_exception_telegram')) {

                throw new Exception($error);

            }

        }

        ##
        # DELETE LOG
        #
        Storage::disk(

            Config::get('tle.path_save')

        )->delete(

            $this->log_name

        );
    }
    /**
     *
     * GET APP NAME
     *
     * @return STRING
     *
     */
    private function getAppName(): string
    {

        $name = null;

        if (Config::get('tle.app_name')) {

            if (strlen(Config::get('tle.app_name')) > 40) {

                $this->appName = Str::limit(

                    Config::get('tle.app_name'), 40

                );

                $name = sprintf(

                    '%s%s',

                    trans('tle::tlemessage.project'),

                    $this->appName

                );

            } else {

                $this->appName = Config::get('tle.app_name');

                $name = sprintf(

                    '%s%s',

                    trans('tle::tlemessage.project'),

                    $this->appName

                );

            }

        } else if (strlen(env('APP_NAME')) > 40) {

            $this->appName = Str::limit(

                env('APP_NAME'), 40

            );

            $name = sprintf(

                '%s%s',

                trans('tle::tlemessage.project'),

                $this->appName

            );

        } else {

            $this->appName = env('APP_NAME');

            $name = sprintf(

                '%s%s',

                trans('tle::tlemessage.project'),

                $this->appName

            );

        }

        return $name;

    }

}
