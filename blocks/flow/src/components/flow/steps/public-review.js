import {useEffect, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import StepLayout from "./components/layout";
import {REVIEW_TARGET_LOGOS} from "./data/data";
import {getPartOfUrl} from "../../../../../../assets/js/helpers/helper";
import {CreateReview} from "../../../rest/rest";

export default function PublicReview({flowId, flowData}) {
    const [loading, setLoading] = useState(0);
    const [disliking, setDisliking] = useState(false);
    const [reviewTargets, setReviewTargets] = useState([]);

    useEffect(() => {
        if (flowData?.utility) {
            // Targets
            setReviewTargets(flowData.utility.targets || []);
        }
    }, [flowData?.utility]);

    const dislikePublicly = async (targetUrl) => {
        setLoading(prev => prev + 1);
        setDisliking(true);
        try {
            const res = await CreateReview(ReviewBird.rest.url, ReviewBird.rest.nonce, {
                flow_uuid: flowId,
                like: 0,
                target: targetUrl,
            });
        } catch (e) {
            console.log(e);
        }
        setDisliking(false);
        setLoading(prev => prev - 1);
        // Redirect
        window.location.href = targetUrl;
    }

    const renderReviewTarget = (reviewTarget, index) => {
        let svgId = false;
        const hostname = getPartOfUrl(reviewTarget.url, 'hostname');
        if (hostname) {
            const i = REVIEW_TARGET_LOGOS.findIndex(item => hostname.indexOf(`${item}.`) !== -1);
            if (i !== -1) {
                svgId = REVIEW_TARGET_LOGOS[i];
            }
        }

        return <button key={index} className='rw-flow-button rw-flow-button-platform'
                       onClick={() => dislikePublicly(reviewTarget.url)} disabled={disliking}>
            {reviewTarget.media_url ?
                <img className='rw-flow-button-platform-pic' src={reviewTarget.media_url} alt='target-logo'/>
                :
                (svgId &&
                    <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                         fill='none' viewBox='0 0 24 24'>
                        <use href={`#rw-flow-${svgId}`}/>
                    </svg>)}
        </button>
    }

    return <StepLayout className={reviewTargets.length > 1 ? 'rw-flow-feedback-public-review-row' : ''}
                       logo={flowData?.utility?.thumbnail_url}>
        <div className="rw-flow-title">
            <p className="rw-flow-title-in">{__("Leave a public review", 'review-bird')}</p>
        </div>
        <div className="rw-flow-label">
            <div className="rw-flow-public-review-desc">
                {reviewTargets.length > 1 &&
                    <p className="rw-flow-public-review-desc-in">{__("Click on the respective platform to leave a public review", 'review-bird')}:</p>}
                {reviewTargets.length === 1 &&
                    <p className="rw-flow-public-review-desc-in">{__("By clicking on the button below you can submit a public review", 'review-bird')}:</p>}
                {!reviewTargets.length &&
                    <p className="rw-flow-public-review-desc-in">{__("There is no target for this flow.", 'review-bird')}:</p>}
            </div>
        </div>
        {reviewTargets.length > 1 &&
            <div className="rw-flow-feedback-actions">
                <div className="rw-flow-feedback-actions-in rw-flow-platform">
                    {reviewTargets.map(renderReviewTarget)}
                </div>
            </div>}
        {reviewTargets.length === 1 &&
            <div className="rw-flow-feedback-actions">
                <div className="rw-flow-feedback-actions-in">
                    <button className='rw-flow-button rw-flow-button-minimal rw-flow-button-primary'
                            onClick={() => window.location.href = reviewTargets[0].url}>
                        <span className='rw-flow-button-desc'>{__("Yes, post publicly", 'review-bird')}</span>
                    </button>
                </div>
            </div>}
    </StepLayout>
}