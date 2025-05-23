import {useEffect, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import {addObjectDataToForm, getSettingsDataToSave} from "../../../../helpers/helper";
import {SETTINGS_KEYS_PREFIX} from "../../data";

export default function SkinSettings({flowData, defaultSettings}) {
    const [loading, setLoading] = useState(0);
    const settings = {
        'skin': useState('blue'),
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
                    <label htmlFor="rw-skin" className="rw-admin-title-in">{__("Select skin", 'review-bird')}</label>
                </th>
                <td className="rw-cont-table-item">
                    <div className="rw-admin-row">
                        <div className="rw-admin-row-in">
                            <select id="rw-skin" className="rw-admin-select"
                                    value={settings['skin'][0]}
                                    onChange={(e) => settings['skin'][1](e.target.value)}>
                                <option value="blue">{__("Blue", 'review-bird')} - {__("White", 'review-bird')} - {__("Black", 'review-bird')}</option>
                                <option value="green">{__("Green", 'review-bird')} - {__("Cream", 'review-bird')} - {__("Dark Grey", 'review-bird')}</option>
                                <option value="braun">{__("Braun", 'review-bird')} - {__("Beige", 'review-bird')} - {__("White", 'review-bird')}</option>
                                <option value="orange">{__("Orange", 'review-bird')} - {__("White", 'review-bird')} - {__("Dark Grey", 'review-bird')}</option>
                                <option value="lilia">{__("Lilia", 'review-bird')} - {__("Light Grey", 'review-bird')} - {__("Dark Blue", 'review-bird')}</option>
                                <option value="rot">{__("Rot", 'review-bird')} - {__("White", 'review-bird')} - {__("Dark Grey", 'review-bird')}</option>
                            </select>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
}