import {useEffect, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import StepLayout from "./components/layout";
import {REVIEW_TARGET_LOGOS} from "./data/data";
import {getPartOfUrl} from "../../../../../../assets/js/helpers/helper";

export default function PublicReview({flowData}) {
    const [reviewTargets, setReviewTargets] = useState([]);

    useEffect(() => {
        if (flowData?.metas?.length) {
            for (const meta of flowData.metas) {
                if (meta.key === 'review_targets') {
                    if (meta.value?.length) {
                        setReviewTargets(meta.value);
                    }
                }
            }
        }
    }, [flowData]);

    const renderReviewTarget = (reviewTarget, index) => {
        let svgId = false;
        const hostname = getPartOfUrl(reviewTarget.url, 'hostname');
        const i = REVIEW_TARGET_LOGOS.findIndex(item => hostname.indexOf(`${item}.`));
        if (i !== -1) {
            svgId = REVIEW_TARGET_LOGOS[i];
        }

        return <button key={index} className='rw-flow-button rw-flow-button-platform'>
            {svgId &&
                <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                     fill='none' viewBox='0 0 24 24'>
                    <use href={`#rw-flow-${svgId}`}/>
                </svg>}
            {reviewTarget.logo_url &&
                <img className='rw-flow-button-platform-img' src={reviewTarget.logo_url} alt={reviewTarget.logo_name}/>}
        </button>
    }

    return <StepLayout className={reviewTargets.length > 0 ? 'rw-flow-feedback-public-review-row' : ''}>
        <div className="rw-flow-title">
            <p className="rw-flow-title-in">{__("Leave a public review", 'review-bird')}</p>
        </div>
        <div className="rw-flow-label">
            <div className="rw-flow-public-review-desc">
                {reviewTargets.length > 0 &&
                    <p className="rw-flow-public-review-desc-in">{__("Click on the respective platform to leave a public review", 'review-bird')}:</p>}
                {!reviewTargets.length &&
                    <p className="rw-flow-public-review-desc-in">{__("By clicking on the button below you can submit a public review", 'review-bird')}:</p>}
            </div>
        </div>
        {reviewTargets.length > 0 &&
            <div className="rw-flow-feedback-actions rw-flow-platform-slide">
                <div className="rw-flow-feedback-actions-in rw-flow-platform-slide-in">
                    {reviewTargets.map(renderReviewTarget)}
                </div>
            </div>}
        {!reviewTargets.length &&
            <div className="rw-flow-feedback-actions">
                <div className="rw-flow-feedback-actions-in">
                    <button className='rw-flow-button rw-flow-button-minimal rw-flow-button-primary'>
                        <span className='rw-flow-button-desc'>{__("Yes, post publicly", 'review-bird')}</span>
                    </button>
                </div>
            </div>}
    </StepLayout>
}