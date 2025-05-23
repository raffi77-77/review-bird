import {useEffect, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import {addObjectDataToForm, getSettingsDataToSave} from "../../../../helpers/helper";
import {SETTINGS_KEYS_PREFIX} from "../../data";

export default function TitleQuestion({flowData, defaultSettings}) {
    const [loading, setLoading] = useState(0);
    const settings = {
        'question': useState(''),
    };

    useEffect(() => {
        getData();
    }, [flowData, defaultSettings]);

    const getData = async () => {
        setLoading(prev => prev + 1);
        try {
            const checkedKeys = [];
            if (flowData?.metas?.length) {
                for (const meta of flowData.metas) {
                    if (meta.meta_key in settings) {
                        settings[meta.meta_key][1](meta.meta_value);
                        checkedKeys.push(meta.meta_key);
                    }
                }
            }
            const missedKeys = Object.keys(settings).filter(item => !checkedKeys.includes(item));
            if (missedKeys.length && defaultSettings?.length) {
                for (const setting of defaultSettings) {
                    const key = setting.key.replace(SETTINGS_KEYS_PREFIX, '');
                    if (missedKeys.includes(key)) {
                        settings[key][1](setting.value);
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
                    <label htmlFor="rw-question"
                           className="rw-admin-title-in">{__("Define Question", 'review-bird')}</label>
                </th>
                <td className="rw-cont-table-item">
                    <div className="rw-admin-row-nested">
                        <div className="rw-admin-row-nested-in">
                              <textarea id="rw-question" className="rw-admin-textarea"
                                        value={settings['question'][0]}
                                        onChange={e => settings['question'][1](e.target.value)}
                                        placeholder={__("Would you recommend {site-name} to others?", 'review-bird')}
                                        rows="3"/>
                        </div>
                        <p className="rw-admin-desc">{"The shortcode {site-name} displays the site name as defined in WordPress under Settings → General."}</p>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
}