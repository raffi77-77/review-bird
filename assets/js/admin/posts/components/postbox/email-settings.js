import {useEffect, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import {addObjectDataToForm, getSettingsDataToSave} from "../../../../helpers/helper";
import {SETTINGS_KEYS_PREFIX} from "../../data";

export default function EmailSettings({flowData, defaultSettings}) {
    const [loading, setLoading] = useState(0);
    const settings = {
        'email_notify_on_negative_review': useState(false),
        'emails_on_negative_review': useState(null),
    };
    const [emails, setEmails] = useState('');

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
                        // Emails
                        if (meta.meta_key === 'emails_on_negative_review' && meta.meta_value.length) {
                            setEmails(meta.meta_value.join(', '));
                        }
                    }
                }
            }
            const missedKeys = Object.keys(settings).filter(item => !checkedKeys.includes(item));
            if (missedKeys.length && defaultSettings?.length) {
                for (const setting of defaultSettings) {
                    const key = setting.key.replace(SETTINGS_KEYS_PREFIX, '');
                    if (missedKeys.includes(key)) {
                        settings[key][1](setting.value);
                        // Emails
                        if (key === 'emails_on_negative_review' && setting.value.length) {
                            setEmails(setting.value.join(', '));
                        }
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

    useEffect(() => {
        const typedEmails = emails.split(',').map(email => email.trim()).filter(email => email);
        settings['emails_on_negative_review'][1](typedEmails?.length ? typedEmails : null);
    }, [emails]);

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
                    <label className="rw-admin-title-in">{__("E-mail", 'review-bird')}</label>
                </th>
                <td className="rw-cont-table-item">
                    <div className="rw-admin-row">
                        <div className="rw-admin-row-in">
                            <input type="checkbox" checked={settings['email_notify_on_negative_review'][0]}
                                   onChange={e => settings['email_notify_on_negative_review'][1](e.target.checked)}/>
                            <p className="rw-admin-desc">{__("When a negative Review is received sent an email", 'review-bird')}</p>
                        </div>
                        <div className="rw-admin-row-in">
                            <input type="text" className="rw-admin-input"
                                   placeholder={__("Emails", 'review-bird')}
                                   value={emails}
                                   onChange={e => setEmails(e.target.value)}/>
                            <p className="rw-admin-desc">{__("Emails will be sent to this email address", 'review-bird')}</p>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
}