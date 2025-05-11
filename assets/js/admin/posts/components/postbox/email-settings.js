import {useEffect, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import {GetFlow} from "../../../../../../blocks/flow/src/rest/rest";
import {addDataToForm, addObjectDataToForm, getSettingsDataToSave} from "../../../../helpers/helper";

export default function EmailSettings({flowData}) {
    const [loading, setLoading] = useState(0);
    const settings = {
        'negative_review_email_send': useState(false),
        'negative_review_email': useState(''),
    };

    useEffect(() => {
        getData();
    }, [flowData]);

    const getData = async () => {
        setLoading(prev => prev + 1);
        try {
            if (flowData?.metas?.length) {
                for (const meta of flowData.metas) {
                    if (meta.key in settings) {
                        settings[meta.key][1](meta.value);
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
                // Meta key
                addDataToForm(form, `metas[${i}][meta_key]`, data[i].key);
                // Meta value
                addObjectDataToForm(form, `metas[${i}][meta_value]`, data[i].value);
            }
        }
    }

    return <div className="rw-skin-content">
        <div className="rw-skin-body rw-admin-body">
            <div className="rw-skin-content-title">
                <h2 className="rw-admin-title-in">{__("E-mail", 'review-bird')}</h2>
            </div>
            <div className="rw-skin-content-in">
                <div className="rw-admin-row">
                    <div className="rw-admin-row-in">
                        <input type="checkbox" checked={settings['negative_review_email_send'][0]}
                               onChange={e => settings['negative_review_email_send'][1](e.target.checked)}/>
                        <p className="rw-admin-desc">{__("When a negative Review is received sent an email", 'review-bird')}</p>
                    </div>
                    <div className="rw-admin-row-in">
                        <input type="text" className="rw-admin-input rw-admin-input-minimal"
                               placeholder="Email"/>
                        <p className="rw-admin-desc">{__("Emails will be sent to this email address", 'review-bird')}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
}