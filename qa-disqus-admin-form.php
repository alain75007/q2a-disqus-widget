<?php

class qa_disqus_admin_form {

    function admin_form() {
        $saved=false;

        if (qa_clicked('disqus_save_button')) {
            qa_opt('disqus_secret_key', qa_post_text('disqus_secret_key_field'));
            qa_opt('disqus_public_key', qa_post_text('disqus_public_key_field'));
            $saved=true;
        }

        return array(
            'ok' => $saved ? 'disqus settings saved.' : null,

            'fields' => array(
                array(
                    'label' => 'Enter Disqus secret_key:',
                    'value' => qa_html(qa_opt('disqus_secret_key')),
                    'tags' => 'NAME="disqus_secret_key_field"',
                ),
                array(
                    'label' => 'Enter Disqus public_key:',
                    'value' => qa_html(qa_opt('disqus_public_key')),
                    'tags' => 'NAME="disqus_public_key_field"',
                ),
            ),

            'buttons' => array(
                array(
                    'label' => 'Save Changes',
                    'tags' => 'NAME="disqus_save_button"',
                ),
            ),

        );


    }
}
