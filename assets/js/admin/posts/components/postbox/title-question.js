import {useEffect, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import {addDataToForm, addObjectDataToForm, getSettingsDataToSave} from "../../../../helpers/helper";

export default function TitleQuestion({flowData}) {
    const [loading, setLoading] = useState(0);
    const settings = {
        'question': useState(''),
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
        <div className="rw-admin-body">
            <div className="rw-skin-content-title">
                <label htmlFor="rw-question"
                       className="rw-admin-title-in">{__("Define Question", 'review-bird')}</label>
            </div>
            <div className="rw-skin-content-in">
                <div className="rw-admin-row">
                    <textarea id="rw-question" className="rw-admin-textarea"
                              value={settings['question'][0]}
                              onChange={e => settings['question'][1](e.target.value)}
                              placeholder={__("Would you recommend {site-name} to others?", 'review-bird')}
                              rows="3"/>
                    <p className="rw-admin-desc">{"The shortcode {site-name} displays the site name as defined in WordPress under Settings → General."}</p>
                </div>
            </div>
        </div>
    </div>
}