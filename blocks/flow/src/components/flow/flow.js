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
    const [theme, setTheme] = useState('blue');

    useEffect(() => {
        getData(id);
    }, [id]);

    useEffect(() => {
        if (flowData?.metas?.length) {
            for (const meta of flowData.metas) {
                // Theme
                if (meta.key === 'theme') {
                    setTheme(meta.value || 'blue');
                }
            }
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

    return <div id="review-bird" className={`rw-flow-theme ${theme}`}>
        <Utilities/>
        <div className='rw-flow-container'>
            {step === 'vote' &&
                <Vote flowId={id} flowData={flowData} setStep={setStep}/>}
            {step === 'review' &&
                <ReviewWithStars flowId={id} flowData={flowData} setStep={setStep}/>}
            {step === 'public-review' &&
                <PublicReview flowData={flowData}/>}
            <div>
                <img src={generalLogo} alt="Logo"/>
            </div>
        </div>
    </div>
}
