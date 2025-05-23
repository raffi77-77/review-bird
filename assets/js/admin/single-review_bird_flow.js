import domReady from '@wordpress/dom-ready';
import {createRoot} from '@wordpress/element';
import TitleQuestion from "./posts/components/postbox/title-question";
import PositiveReviewResponse from './posts/components/postbox/positive-review-response';
import NegativeReviewResponse from "./posts/components/postbox/negative-review-response";
import EmailSettings from "./posts/components/postbox/email-settings";
import {GetFlow, GetSettings} from "../../../blocks/flow/src/rest/rest";
import {SETTINGS_KEYS_PREFIX} from "./posts/data";

domReady(async () => {
    let flowData = null;
    let settings = [];
    const settingsKeys = [
        'question',
        'targets',
        'target_distribution',
        'multiple_targets',
        'review_box_text',
        'username_placeholder',
        'review_placeholder',
        'success_message',
        'gating',
        'email_notify_on_negative_review',
        'emails_on_negative_review',
    ];
    try {
        flowData = await GetFlow(ReviewBird.rest.url, ReviewBird.rest.nonce, ReviewBird.flow_uuid, {
            include: ['metas']
        });
        settings = await GetSettings(ReviewBird.rest.url, ReviewBird.rest.nonce, {
            key: settingsKeys.map(key => `${SETTINGS_KEYS_PREFIX}${key}`)
        });
    } catch (e) {
        console.error(e);
    }

    const titleQuestion = document.querySelector('#title-question .inside');
    if (titleQuestion) {
        const root = createRoot(titleQuestion);

        root.render(<TitleQuestion flowData={flowData} defaultSettings={settings}/>);
    }

    const positiveReviewResponse = document.querySelector('#positive-review-response .inside');
    if (positiveReviewResponse) {
        const root = createRoot(positiveReviewResponse);

        root.render(<PositiveReviewResponse flowData={flowData} defaultSettings={settings}/>);
    }

    const negativeReviewResponse = document.querySelector('#negative-review-response .inside');
    if (negativeReviewResponse) {
        const root = createRoot(negativeReviewResponse);

        root.render(<NegativeReviewResponse flowData={flowData} defaultSettings={settings}/>);
    }

    const emailSettings = document.querySelector('#email-settings .inside');
    if (emailSettings) {
        const root = createRoot(emailSettings);

        root.render(<EmailSettings flowData={flowData} defaultSettings={settings}/>);
    }
});