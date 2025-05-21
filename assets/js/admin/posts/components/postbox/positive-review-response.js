import {useEffect, useState} from "@wordpress/element";
import {__, sprintf} from "@wordpress/i18n";
import Tooltip from "../../../components/tooltip";
import {addObjectDataToForm, getPartOfUrl, getSettingsDataToSave} from "../../../../helpers/helper";
import MediaUploaderButton from "../../../components/media-uploader-button";
import {REVIEW_TARGET_LOGOS} from "../../../../../../blocks/flow/src/components/flow/steps/data/data";
import Utilities from "../../../../../../blocks/flow/src/utilities";

const REVIEW_TARGET_DISTRIBUTIONS = [
    {
        1: [50, 50],
        6: [33, 33, 34],
        11: [25, 25, 25, 25],
    },
    {
        2: [60, 40],
        7: [50, 25, 25],
        12: [40, 20, 20, 20],
    },
    {
        3: [70, 30],
        8: [60, 25, 15],
        13: [50, 20, 15, 15],
    },
    {
        4: [80, 20],
        9: [70, 20, 10],
        14: [60, 20, 10, 10],
    },
    {
        5: [90, 10],
        10: [80, 15, 5],
        15: [70, 15, 10, 5],
    },
];

export default function PositiveReviewResponse({flowData}) {
    const [loading, setLoading] = useState(0);
    const settings = {
        'targets': useState([
            {
                url: '',
                media_id: null,
            }
        ]),
        'target_distribution': useState(null),
        'multiple_targets': useState(false),
    };

    useEffect(() => {
        if (settings['targets'][0].length > 1) {
            settings['target_distribution'][1]((settings['targets'][0].length - 1) * 5 - 4);
        } else {
            settings['target_distribution'][1](null);
        }
    }, [settings['targets'][0].length]);

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
                let value = data[i].value;
                // Prepare targets data
                if (data[i].key === 'targets') {
                    value = data[i].value.map((reviewTarget, index) => ({
                        url: reviewTarget.url,
                        media_id: reviewTarget.media_id ? reviewTarget.media_id : reviewTarget.media?.id || null,
                    }));
                }
                // Meta
                addObjectDataToForm(form, `metas[${data[i].key}]`, value);
            }
        }
    }

    /**
     * Render review target
     *
     * @param {object} currentReviewTarget Current review target
     * @param {number} index Index
     * @return {JSX.Element}
     */
    const renderReviewTarget = (currentReviewTarget, index) => {
        const currentIndex = index + 1;
        let svgId;
        const hostname = getPartOfUrl(currentReviewTarget.url, 'hostname');
        if (hostname) {
            const i = REVIEW_TARGET_LOGOS.findIndex(item => hostname.indexOf(`${item}.`) !== -1);
            if (i !== -1) {
                svgId = REVIEW_TARGET_LOGOS[i];
            } else {
                svgId = false;
            }
        } else {
            svgId = false;
        }

        return <tr key={currentIndex} className="rw-cont-table-in">
            <th className="rw-cont-table-item-title">
                <div className="rw-admin-label">
                    <label className="rw-admin-title">{__("Target", 'review-bird')} #{currentIndex + 1}:</label>
                    {/*<svg className="rw-admin-label-tooltip-in" viewBox="-0.5 0 48 48"
                             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="Icons" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                <g id="Color-" transform="translate(-401.000000, -860.000000)">
                                    <g id="Google" transform="translate(401.000000, 860.000000)">
                                        <path
                                            d="M9.82727273,24 C9.82727273,22.4757333 10.0804318,21.0144 10.5322727,19.6437333 L2.62345455,13.6042667 C1.08206818,16.7338667 0.213636364,20.2602667 0.213636364,24 C0.213636364,27.7365333 1.081,31.2608 2.62025,34.3882667 L10.5247955,28.3370667 C10.0772273,26.9728 9.82727273,25.5168 9.82727273,24"
                                            id="Fill-1" fill="#FBBC05">

                                        </path>
                                        <path
                                            d="M23.7136364,10.1333333 C27.025,10.1333333 30.0159091,11.3066667 32.3659091,13.2266667 L39.2022727,6.4 C35.0363636,2.77333333 29.6954545,0.533333333 23.7136364,0.533333333 C14.4268636,0.533333333 6.44540909,5.84426667 2.62345455,13.6042667 L10.5322727,19.6437333 C12.3545909,14.112 17.5491591,10.1333333 23.7136364,10.1333333"
                                            id="Fill-2" fill="#EB4335">

                                        </path>
                                        <path
                                            d="M23.7136364,37.8666667 C17.5491591,37.8666667 12.3545909,33.888 10.5322727,28.3562667 L2.62345455,34.3946667 C6.44540909,42.1557333 14.4268636,47.4666667 23.7136364,47.4666667 C29.4455,47.4666667 34.9177955,45.4314667 39.0249545,41.6181333 L31.5177727,35.8144 C29.3995682,37.1488 26.7323182,37.8666667 23.7136364,37.8666667"
                                            id="Fill-3" fill="#34A853">

                                        </path>
                                        <path
                                            d="M46.1454545,24 C46.1454545,22.6133333 45.9318182,21.12 45.6113636,19.7333333 L23.7136364,19.7333333 L23.7136364,28.8 L36.3181818,28.8 C35.6879545,31.8912 33.9724545,34.2677333 31.5177727,35.8144 L39.0249545,41.6181333 C43.3393409,37.6138667 46.1454545,31.6490667 46.1454545,24"
                                            id="Fill-4" fill="#4285F4">

                                        </path>
                                    </g>
                                </g>
                            </g>
                        </svg>*/}
                    <MediaUploaderButton
                        className='rw-button-upload-media update'
                        onSelect={media => settings['targets'][1](prevState =>
                            prevState.map((reviewTarget, i) => {
                                if (i === currentIndex) {
                                    return {
                                        ...reviewTarget,
                                        media_id: media.id,
                                        media: media,
                                    }
                                }
                                return reviewTarget;
                            })
                        )}>
                        {currentReviewTarget.media?.sizes?.thumbnail ?
                            <img className='rw-button-upload-media-pic'
                                 width={currentReviewTarget.media.sizes.thumbnail.width}
                                 height={currentReviewTarget.media.sizes.thumbnail.height}
                                 src={currentReviewTarget.media.sizes.thumbnail.url}
                                 alt={currentReviewTarget.media.alt}/>
                            :
                            (
                                svgId ?
                                    <svg className='rw-admin-label-tooltip-in' xmlns='http://www.w3.org/2000/svg'
                                         fill='none' viewBox='0 0 24 24'>
                                        <use href={`#rw-flow-${svgId}`}/>
                                    </svg>
                                    :
                                    <svg className="rw-admin-i rw-button-upload-media-i"
                                         xmlns="http://www.w3.org/2000/svg" height="24px"
                                         viewBox="0 -960 960 960" width="24px">
                                        <path
                                            d="M440-200h80v-167l64 64 56-57-160-160-160 160 57 56 63-63v167ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/>
                                    </svg>
                            )}
                    </MediaUploaderButton>
                </div>
            </th>
            <td className="rw-cont-table-item">
                <div className="rw-admin-row rw-admin-row-nested">
                    <input type="text" placeholder="https://" className="rw-admin-input"
                           value={currentReviewTarget.url}
                           onChange={(e) => settings['targets'][1](prevState =>
                               prevState.map((reviewTarget, i) => {
                                   if (i === currentIndex) {
                                       return {
                                           ...reviewTarget,
                                           url: e.target.value
                                       }
                                   }
                                   return reviewTarget;
                               })
                           )}/>
                </div>
            </td>
        </tr>
    }

    const renderReviewTargetDistributionRow = (distributionsRow, index) => {
        return <tr key={index} className="rw-admin-table-body-in">
            {Object.keys(distributionsRow).map((key, index) => {
                const value = +key;

                return <td key={key}
                           className={`rw-admin-table-body-item clickable${settings['target_distribution'][0] === value ? ' selected' : ''}${distributionsRow[key].length !== settings['targets'][0].length ? ' disabled' : ''}`}
                           onClick={() => settings['target_distribution'][1](value)}>
                    <span className="rw-admin-table-desc">{distributionsRow[key].map(item => `${item}%`).join(' / ')}</span>
                </td>
            })}
        </tr>
    }

    return <div className="rw-skin-content">
        <Utilities/>
        <table className="rw-cont-table">
            <tbody className="rw-cont-table-tbody">
            <tr className="rw-cont-table-in">
                <th className="rw-cont-table-item-title">
                    <label htmlFor="review-target-main"
                           className="rw-admin-title-in">{__("Review Target", 'review-bird')} #1</label>
                </th>
                <td className="rw-cont-table-item">
                    {/*TODO - icon is missing*/}
                    <input id="review-target-main" type="text" className="rw-admin-input"
                           placeholder="https://"
                           value={settings['targets'][0][0]?.url || ''}
                           onChange={(e) => settings['targets'][1](prevState =>
                               prevState.map((reviewTarget, index) => {
                                   if (index === 0) {
                                       return {
                                           ...reviewTarget,
                                           url: e.target.value
                                       }
                                   }
                                   return reviewTarget;
                               }))}/>
                    <p className="rw-admin-desc">{__("This field is required.", 'review-bird')}</p>
                    <div className="rw-admin-label">
                        <Tooltip title="⭐ Quick Review Link Guide by Platform" subTitle="Is there a quick review link?">
                            <p className="rw-admin-desc">
                                1. Google
                                ✅ Yes – Use the Google Place ID to generate a direct link:
                                https://search.google.com/local/writereview?placeid=YOUR_PLACE_ID
                                Find your Place ID here:
                                <a href="#">👉
                                    https://developers.google.com/maps/documentation/places/web-service/place-id</a>
                            </p>
                            <p className="rw-admin-desc">
                                2. Facebook
                                ✅ Yes, but a bit more complex.
                                Direct link to review tab (if enabled on page):
                                <a href="#">https://www.facebook.com/YOUR_PAGE_USERNAME/reviews/</a>
                                ⚠️ Note: The review feature must be turned on for the Facebook page.
                            </p>
                            <p className="rw-admin-desc">
                                3. Yelp
                                🔶 Partially – No official direct link to the review form.
                                Best option:
                                Link to business page, review section will show if user is logged in:
                                <a href="#">https://www.yelp.com/biz/YOUR-BUSINESS-NAME</a>
                            </p>
                            <p className="rw-admin-desc">
                                4. Amazon
                                ❌ No – There is no direct "quick review" link.
                                Users must go to their orders → select product → leave a review.
                            </p>
                            <p className="rw-admin-desc">
                                5. Audible
                                🔶 Indirect – Reviews are done via Amazon.
                                No separate direct link for Audible-specific content.
                            </p>
                            <p className="rw-admin-desc">
                                6. iTunes / Apple Music
                                ❌ No direct review link for apps, music, or podcasts.
                                Users must open iTunes/Apple Podcasts and leave a review within the app.
                            </p>
                            <p className="rw-admin-desc">
                                7. Apple App Store
                                🔶 No direct link to review form, but you can link to the app's page:
                                <a href="#">https://apps.apple.com/app/idYOUR_APP_ID</a>
                                Users can scroll to "Ratings & Reviews" and click "Write a Review."
                            </p>
                            <p className="rw-admin-desc">
                                8. Google Play
                                ✅ Yes – Direct link to your app's page:
                                <a href="#">https://play.google.com/store/apps/details?id=YOUR_APP_PACKAGE_NAME</a>
                                Users can click "Rate this app" directly from there.
                            </p>
                            <p className="rw-admin-desc">
                                9. Foursquare
                                ❌ No quick review link – Users must search for the venue manually in the app or
                                site.
                            </p>
                            <p className="rw-admin-desc">
                                10. WordPress
                                🔶 Depends – If you're asking for plugin or theme reviews on WordPress.org:
                                Direct link:
                                <a href="#">https://wordpress.org/support/plugin/PLUGIN-SLUG/reviews/#new-post
                                </a>
                                Replace PLUGIN-SLUG with your plugin's slug.
                            </p>
                            <p className="rw-admin-desc">
                                11. Etsy
                                ❌ No direct review link – Reviews are only allowed for verified purchases, and
                                must
                                be left through the buyer’s account.
                            </p>
                            <p className="rw-admin-desc">
                                12. YouTube
                                ✅ Yes – To make a subscribe link for your YouTube channel, just add
                                ? sub_confirmation=1 to the end of your YouTube channel's URL
                            </p>
                        </Tooltip>
                        <p className="rw-admin-desc">{__("How to find the right URL", 'review-bird')}</p>
                    </div>
                </td>
            </tr>

            <tr className="rw-cont-table-in">
                <th className="rw-cont-table-item-title">
                    <label htmlFor="rw-multiple-targets"
                           className="rw-admin-title-in">{__("Enable Multiple Targets", 'review-bird')}</label>
                </th>
                <td className="rw-cont-table-item">
                    <div className="rw-admin-row">
                        <div className="rw-admin-row-in">
                            <div className="rw-admin-label">
                                <div
                                    className={`rw-skin-select-radio${settings['multiple_targets'][0] ? ' active' : ''}`}>
                                    <input id="rw-multiple-targets-yes" type="radio"
                                           className="rw-skin-select-radio-in" value={1}
                                           checked={settings['multiple_targets'][0]}
                                           onChange={() => settings['multiple_targets'][1](true)}/>
                                </div>
                                <label htmlFor="rw-multiple-targets-yes"
                                       className="rw-admin-desc-in">{__("Yes", 'review-bird')}</label>
                            </div>
                            <div className="rw-admin-label">
                                <div
                                    className={`rw-skin-select-radio${!settings['multiple_targets'][0] ? ' active' : ''}`}>
                                    <input id="rw-multiple-targets-no" type="radio"
                                           className="rw-skin-select-radio-in" value={0}
                                           checked={!settings['multiple_targets'][0]}
                                           onChange={() => settings['multiple_targets'][1](false)}/>
                                </div>
                                <label htmlFor="rw-multiple-targets-no"
                                       className="rw-admin-desc-in">{__("No", 'review-bird')}</label>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            {settings['multiple_targets'][0] && settings['targets'][0].length > 1 && settings['targets'][0].slice(1).map(renderReviewTarget)}
            {settings['multiple_targets'][0] && settings['targets'][0].length < 4 &&
                <tr className="rw-cont-table-in">
                    <th className="rw-cont-table-item-title">
                        <div className="rw-skin-content-title rw-admin-title">
                            <button type="button" className="rw-admin-add"
                                    onClick={() => settings['targets'][1](prevState => [...prevState, {
                                        url: '',
                                        media_id: null,
                                    }])}>
                                <svg className="rw-admin-i rw-admin-add-i" xmlns="http://www.w3.org/2000/svg"
                                     height="24px"
                                     viewBox="0 -960 960 960">
                                    <path
                                        d="M440-280h80v-160h160v-80H520v-160h-80v160H280v80h160v160Zm40 200q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/>
                                </svg>
                            </button>
                        </div>
                    </th>
                    <td className="rw-cont-table-item">
                    </td>
                </tr>}

            {settings['multiple_targets'][0] && settings['targets'][0].length > 1 &&
                <tr className="rw-cont-table-in">
                    <th className="rw-cont-table-item-title distribution">
                        <label
                            className=" rw-admin-table-title-in">{__("Review Target Distribution", 'review-bird')}</label>
                    </th>
                    <td className="rw-cont-table-item">
                        <table className="rw-admin-table">
                            <thead className="rw-admin-table-head">
                            <tr className="rw-admin-table-head-in">
                                <th className="rw-admin-table-head-item">
                                    <span
                                        className="rw-admin-table-head-desc">{sprintf(__("if %d targets are chosen", 'review-bird'), 2)}</span>
                                </th>
                                <th className="rw-admin-table-head-item">
                                    <span
                                        className="rw-admin-table-head-desc">{sprintf(__("if %d targets are chosen", 'review-bird'), 3)}</span>
                                </th>
                                <th className="rw-admin-table-head-item">
                                    <span
                                        className="rw-admin-table-head-desc">{sprintf(__("if %d targets are chosen", 'review-bird'), 4)}</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody className="rw-admin-table-body">
                            {REVIEW_TARGET_DISTRIBUTIONS.map(renderReviewTargetDistributionRow)}
                            </tbody>
                        </table>
                    </td>
                </tr>}
            </tbody>
        </table>
    </div>
}