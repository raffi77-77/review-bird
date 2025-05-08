import {useEffect, useState} from "@wordpress/element";
import Utilities from "../../utilities/index"
import generalLogo from "../../../../../resources/logo/general-logo.svg"
import Vote from "./steps/vote";
import ReviewWithStars from "./steps/review-with-stars";
import PublicReview from "./steps/public-review";
import {GetFlow} from "../../rest/rest";

export default function Flow({id, attributes}) {
    const [loading, setLoading] = useState(0);
    const [step, setStep] = useState('vote');
    const [flowData, setFlowData] = useState(null);
    const [destinations, setDestinations] = useState([
        'youtube',
        'wordpress',
        'forsquare',
        'google-play',
        'app-store',
        'audible',
        'trustpilot',
        'doctolib',
        'jameda',
    ]);
    const [theme, setTheme] = useState('rw-flow-theme blue');
    const [reviewGating, setReviewGating] = useState(true);

    useEffect(() => {
        getData(id);
    }, [id]);

    useEffect(() => {
        if (flowData?.metas?.length) {
            // TODO - set destinations
            // Theme
            const flowTheme = flowData.metas.find(meta => meta.key === 'theme')?.value || 'blue';
            setTheme(`rw-flow-theme ${flowTheme}`);
            // Review gating
            setReviewGating(!flowData.metas.find(meta => meta.key === 'review_gating_off')?.value);
        }
    }, [flowData?.metas]);

    const getData = async (flowId) => {
        setLoading(prev => prev + 1);
        try {
            const res = await GetFlow(ReviewBird.rest.url, ReviewBird.rest.nonce, flowId, {
                include: ['metas']
            });
            setFlowData(res);
        } catch (e) {
            console.log(e);
        }
        setLoading(prev => prev - 1);
    }

    return <div id="review-bird" className={theme}>
        <Utilities/>
        <div className='rw-flow-container'>
            {step === 'vote' &&
                <Vote flowId={id} setStep={setStep}/>}
            {step === 'review' &&
                <ReviewWithStars flowId={id} reviewGating={reviewGating} setStep={setStep}/>}
            {step === 'public-review' &&
                <PublicReview destinations={destinations}/>}
            <div>
                <img src={generalLogo} alt="Logo"/>
            </div>
        </div>
    </div>
}
