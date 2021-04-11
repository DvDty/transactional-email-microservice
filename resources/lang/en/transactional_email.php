<?php

return [
    'cli' => [
        'email_type_prompt'          => 'What type is the email?',
        'text_type'                  => 'Text',
        'html_type'                  => 'HTML',
        'subject_prompt'             => 'Enter subject',
        'content_explanation'        => 'Until receiving "ready", you will be asked to enter content lines.',
        'content_line_prompt'        => 'Add line',
        'recipients_explanation'     => 'Until receiving "ready", you will be asked to enter recipients.',
        'recipient_prompt'           => 'Add recipient',
        'recipient_validation_error' => ':recipient is not a valid email. Skipping.',
        'confirmation_prompt'        => 'You are about to send the ":subject" to :recipientCount email addresses?',
        'success_message'            => 'The emails were successfully added to the queue.',
        'exiting_message'            => 'Exiting.',
    ],
];
