<?php

namespace Modules\Setting\Http\Controllers;

use App\AppHelpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Modules\Setting\Model\Language;
use Modules\Setting\Model\MailConfig;


class SettingController extends Controller {

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        # parent::__construct();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request) {
        return view("Setting::index");
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function emailConfig(Request $request) {
        $post        = $request->post();
        $mail_config = MailConfig::getMailConfig();
        if($post) {
            unset($post['_token']);
            foreach($post as $key => $value) {
                $mail_config = MailConfig::where('key', $key)->first();
                if(!empty($mail_config)) {
                    $mail_config->update(['value' => $value]);
                } else {
                    $mail_config        = new MailConfig();
                    $mail_config->key   = $key;
                    $mail_config->value = $value;
                    $mail_config->save();
                }
            }

            $request->session()->flash('success', 'Email Config updated successfully.');

            return redirect()->back();
        }

        return view("Setting::email", compact('mail_config'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function langManagement(Request $request) {
        $post = $request->post();
        $lang = new Language();

        if($post) {
            $request->session()->flash('success', 'Feature not released yet, we will make it soon.');
            return redirect()->back();
        }

        return view("Setting::language", compact('lang'));
    }

    /**
     * @return RedirectResponse
     */
    public function testSendMail(Request $request) {
        $mail_to = 'phuchp.613@gmai.com';
        $subject = 'Test email';
        $title   = 'Test email function';
        $body    = 'We are testing email!';
        $send = Helper::sendMail($mail_to, $subject, $title, $body);
        if($send){
            $request->session()->flash('success', 'Mail send successfully');
        }else{
            $request->session()->flash('error', trans('Can not send email. Please check your Email config.'));
        }
        return redirect()->back();
    }
}
