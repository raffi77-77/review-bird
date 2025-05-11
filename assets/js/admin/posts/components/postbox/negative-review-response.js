import {useEffect, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import Tooltip from "../../../components/tooltip";
import {GetFlow} from "../../../../../../blocks/flow/src/rest/rest";
import {addDataToForm, addObjectDataToForm, getSettingsDataToSave} from "../../../../helpers/helper";

export default function NegativeReviewResponse() {
    const [loading, setLoading] = useState(0);
    const [flowData, setFlowData] = useState(null);
    const settings = {
        'question': useState(''),
        'field_name_placeholder': useState(''),
        'field_review_placeholder': useState(''),
        'success_message': useState(''),
        'review_gating_off': useState(false),
    };

    useEffect(() => {
        getData();
    }, []);

    const getData = async () => {
        setLoading(prev => prev + 1);
        try {
            const res = await GetFlow(ReviewBird.rest.url, ReviewBird.rest.nonce, ReviewBird.flow_uuid, {
                include: ['metas']
            });
            if (res.metas.length) {
                for (const meta of res.metas) {
                    if (meta.key in settings) {
                        settings[meta.key][1](meta.value);
                    }
                }
            }
            setFlowData(res);
        } catch (e) {
            console.error(e);
        }
        setLoading(prev => prev - 1);
    }

    /**
     * Handle form#post submission
     */
    useEffect(() => {
        // form#post submission
        document.querySelector('#post').addEventListener('submit', handleSubmit);

        return () => {
            document.querySelector('#post').removeEventListener('submit', handleSubmit);
        }
    }, [settings]);

    /**
     * Handle post form submit
     *
     * @param e
     */
    const handleSubmit = (e) => {
        const data = getSettingsDataToSave(settings);
        if (data.length) {
            const form = e.target;
            // Add fields to form data
            for (const i in data) {
                // Meta key
                addDataToForm(form, `metas[${i}][meta_key]`, data[i].key);
                // Meta value
                addObjectDataToForm(form, `metas[${i}][meta_value]`, data[i].value);
            }
        }
    }

    return <div className="rw-skin-content">
        <div className="rw-admin-body">
            <div className="rw-skin-content-title">
                <h2 className="rw-admin-title-in">{__("Review-Box Text", 'review-bird')}</h2>
            </div>
            <div className="rw-skin-content-in">
                <div className="rw-admin-row">
                    <textarea className="rw-admin-textarea"
                              value={settings['question'][0]}
                              onChange={e => settings['question'][1](e.target.value)}
                              placeholder={__("Would you recommend {site-name} to others?", 'review-bird')}
                              rows="3"/>
                </div>
            </div>
        </div>
        <div className="rw-admin-body">
            <div className="rw-skin-content-title">
                <h2 className="rw-admin-title-in">{__("Placeholder Text Form Fields", 'review-bird')}</h2>
            </div>
        </div>
        <div className="rw-admin-body">
            <div className="rw-skin-content-title"/>
            <div className="rw-skin-content-in">
                <div className="rw-admin-row">
                    <div className="rw-admin-row-nested">
                        <input type="text" className="rw-admin-input"
                               value={settings['field_name_placeholder'][0]}
                               onChange={e => settings['field_name_placeholder'][1](e.target.value)}
                               placeholder={__("Enter your name (Optional)", 'review-bird')}/>
                        <p className="rw-admin-desc">{__("This is the text for the name", 'review-bird')}</p>
                    </div>
                    <input type="text" className="rw-admin-input"
                           value={settings['field_review_placeholder'][0]}
                           onChange={e => settings['field_review_placeholder'][1](e.target.value)}
                           placeholder={__("Share your impressions and experiences (Optional)", 'review-bird')}/>
                    <p className="rw-admin-desc">{__("This is the text for the review field itself", 'review-bird')}</p>
                </div>
            </div>
        </div>
        <div className="rw-admin-body">
            <div className="rw-skin-content-title">
                <h2 className="rw-admin-title-in">{__("Enter success Message", 'review-bird')}</h2>
            </div>
        </div>
        <div className="rw-admin-body">
            <div className="rw-skin-content-title"/>
            <div className="rw-skin-content-in">
                <div className="rw-admin-row">
                    <div className="rw-admin-row-nested">
                        <input type="text" className="rw-admin-input"
                               value={settings['success_message'][0]}
                               onChange={e => settings['success_message'][1](e.target.value)}
                               placeholder={__("Your review was submitted successfully", 'review-bird')}/>
                    </div>
                </div>
            </div>
        </div>
        <div className="rw-admin-body">
            <div className="rw-skin-content-title rw-admin-title active">
                <h2 className="rw-admin-title-in">{__("Turn off Review Gating", 'review-bird')}</h2>
                <Tooltip title="Why Review Gating Hurts Your Business">
                    <p className="rw-admin-desc">Review gating is harmful because it filters out negative feedback,
                        giving an unrealistic and overly positive impression of your business. Not only does this damage
                        customer trust when they notice the lack of honest criticism, but it also violates the terms of
                        service of major review platforms like Google and Yelp. These platforms strictly prohibit review
                        gating, and breaking their rules can result in penalties or removal of your business profile. In
                        contrast, a few bad reviews can actually benefit your reputation—they show that your review
                        profile is genuine, helping potential customers trust that the feedback is real and
                        unfiltered.</p>
                </Tooltip>
            </div>
        </div>
        <div className="rw-admin-body">
            <div className="rw-skin-content-title"/>
            <div className="rw-skin-content-in">
                <div className="rw-admin-row">
                    <div className="rw-admin-row-in">
                        <input type="checkbox"
                               checked={settings['review_gating_off'][0]}
                               onChange={e => settings['review_gating_off'][1](e.target.checked)}/>
                        <p className="rw-admin-desc">{__("Enable negative responses to be forwarded to the review destination.", 'review-bird')}</p>
                    </div>
                    <div className="rw-admin-row-in">
                        <p className="rw-admin-desc">{__("Review gating typically violates the terms and conditions of review platforms (such as Google, Trustpilot, Yelp, etc.). To comply with their policies—specifically the rule against selectively requesting reviews only from users with positive feedback—make sure to keep review gating disabled.", 'review-bird')}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
}