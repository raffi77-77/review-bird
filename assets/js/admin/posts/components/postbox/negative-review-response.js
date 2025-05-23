import {useEffect, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import Tooltip from "../../../components/tooltip";
import {addObjectDataToForm, getSettingsDataToSave} from "../../../../helpers/helper";

export default function NegativeReviewResponse({flowData}) {
    const [loading, setLoading] = useState(0);
    const settings = {
        'review_box_text': useState(''),
        'username_placeholder': useState(''),
        'review_placeholder': useState(''),
        'success_message': useState(''),
        'gating': useState(true),
    };

    useEffect(() => {
        getData();
    }, [flowData]);

    const getData = async () => {
        setLoading(prev => prev + 1);
        try {
            if (flowData?.utility) {
                for (const key of Object.keys(settings)) {
                    if (key in flowData.utility) {
                        settings[key][1](flowData.utility[key]);
                    }
                }
            }
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
                // Meta
                addObjectDataToForm(form, `metas[${data[i].key}]`, data[i].value);
            }
        }
    }

    return <div className="rw-skin-content">
        <table className="rw-cont-table">
            <tbody className="rw-cont-table-tbody">
            <tr className="rw-cont-table-in">
                <th className="rw-cont-table-item-title">
                    <label htmlFor="rw-negative-review-question"
                           className="rw-admin-title-in">{__("Review-Box Text", 'review-bird')}</label>
                </th>
                <td className="rw-cont-table-item">
                    <div className="rw-admin-row-nested">
                        <div className="rw-admin-row-nested-in">
                            <textarea id="rw-negative-review-question" className="rw-admin-textarea"
                                      value={settings['review_box_text'][0]}
                                      onChange={e => settings['review_box_text'][1](e.target.value)}
                                      placeholder={__("Would you recommend {site-name} to others?", 'review-bird')}
                                      rows="3"/>
                        </div>
                    </div>
                </td>
            </tr>
            <tr className="rw-cont-table-in">
                <th className="rw-cont-table-item-title">
                    <label className="rw-admin-title-in">{__("Placeholder Text Form Fields", 'review-bird')}</label>
                </th>
                <td className="rw-cont-table-item">
                    <div className="rw-admin-row-nested">
                        <div className="rw-admin-row-nested-in">
                            <input type="text" className="rw-admin-input"
                                   value={settings['username_placeholder'][0]}
                                   onChange={e => settings['username_placeholder'][1](e.target.value)}
                                   placeholder={__("Enter your name (Optional)", 'review-bird')}/>
                        </div>
                        <p className="rw-admin-desc">{__("This is the text for the name", 'review-bird')}</p>
                    </div>
                    <div className="rw-admin-row-nested">
                        <div className="rw-admin-row-nested-in">
                            <input type="text" className="rw-admin-input"
                                   value={settings['review_placeholder'][0]}
                                   onChange={e => settings['review_placeholder'][1](e.target.value)}
                                   placeholder={__("Share your impressions and experiences (Optional)", 'review-bird')}/>
                        </div>
                        <p className="rw-admin-desc">{__("This is the text for the review field itself", 'review-bird')}</p>
                    </div>
                </td>
            </tr>
            <tr className="rw-cont-table-in">
                <th className="rw-cont-table-item-title">
                    <label htmlFor="rw-negative-success-message"
                           className="rw-admin-title-in">{__("Enter success Message", 'review-bird')}</label>
                </th>
                <td className="rw-cont-table-item">
                    <div className="rw-admin-row-nested">
                        <div className="rw-admin-row-nested-in">
                            <input id="rw-negative-success-message" type="text" className="rw-admin-input"
                                   value={settings['success_message'][0]}
                                   onChange={e => settings['success_message'][1](e.target.value)}
                                   placeholder={__("Your review was submitted successfully", 'review-bird')}/>
                        </div>
                    </div>
                </td>
            </tr>
            <tr className="rw-cont-table-in">
                <th className="rw-cont-table-item-title nested">
                    <div className="rw-skin-content-title rw-admin-title active">
                        <label className="rw-admin-title">{__("Turn off Review Gating", 'review-bird')}</label>
                        <Tooltip title="Why Review Gating Hurts Your Business">
                            <p className="rw-admin-desc">Review gating is harmful because it filters out negative
                                feedback,
                                giving an unrealistic and overly positive impression of your business. Not only does
                                this damage
                                customer trust when they notice the lack of honest criticism, but it also violates the
                                terms of
                                service of major review platforms like Google and Yelp. These platforms strictly
                                prohibit review
                                gating, and breaking their rules can result in penalties or removal of your business
                                profile. In
                                contrast, a few bad reviews can actually benefit your reputation—they show that your
                                review
                                profile is genuine, helping potential customers trust that the feedback is real and
                                unfiltered.</p>
                        </Tooltip>
                    </div>
                </th>
                <td className="rw-cont-table-item">
                    <div className="rw-admin-row">
                        <div className="rw-admin-row-in">
                            <input type="checkbox"
                                   className='rw-admin-row-input'
                                   checked={!settings['gating'][0]}
                                   onChange={e => settings['gating'][1](!e.target.checked)}/>
                            <p className="rw-admin-desc">{__("Enable negative responses to be forwarded to the review destination.", 'review-bird')}</p>
                        </div>
                        <div className="rw-admin-row-in rw-admin-row-nested">
                            <p className="rw-admin-desc">{__("Review gating typically violates the terms and conditions of review platforms (such as Google, Trustpilot, Yelp, etc.). To comply with their policies—specifically the rule against selectively requesting reviews only from users with positive feedback—make sure to keep review gating disabled.", 'review-bird')}</p>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
}