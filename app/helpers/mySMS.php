<?php

class MySMS {

    public static function sendAddedToBinaryTree($firstName, $lastName, $distId, $mobile) {
        $template = App\MailTemplate::getRec(App\MailTemplate::TYPE_ADDED_TO_BINARY_TREE_SMS);
        if ($template->is_active == 1) {
            $subject = $template->subject;
            $content = $template->content;
            // replace place holders
            $content = str_replace("<dist_first_name>", $firstName, $content);
            $content = str_replace("<dist_last_name>", $lastName, $content);
            $content = str_replace("<dist_id>", $distId, $content);
            //
            \App\Twilio::sendSMS($mobile, $content);
        }
    }

}

?>