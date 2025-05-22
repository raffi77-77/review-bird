import {useEffect, useState} from "@wordpress/element";
import Utilities from "../../utilities/index"
import generalLogo from "../../../../../resources/logo/general-logo.svg"
import Vote from "./steps/vote";
import ReviewWithStars from "./steps/review-with-stars";
import PublicReview from "./steps/public-review";
import {GetFlow} from "../../rest/rest";
import Container from "./steps/components/container";

export default function Flow({id, attributes}) {
    const [loading, setLoading] = useState(0);
    const [isDataFetched, setIsDataFetched] = useState(false);
    const [step, setStep] = useState('vote');
    const [flowData, setFlowData] = useState(null);
    const [theme, setTheme] = useState(false);

    useEffect(() => {
        getData(id);
    }, [id]);

    useEffect(() => {
        if (isDataFetched) {
            // Theme
            setTheme(flowData?.utility?.skin || 'blue');
        }
    }, [isDataFetched, flowData?.utility]);

    const getData = async (flowId) => {
        setLoading(prev => prev + 1);
        try {
            const res = await GetFlow(ReviewBird.rest.url, ReviewBird.rest.nonce, flowId, {
                include: ['utility']
            });
            setFlowData(res);
        } catch (e) {
            console.log(e);
        }
        setIsDataFetched(true);
        setLoading(prev => prev - 1);
    }

    return <div id="review-bird"
                className={`rw-flow-theme${!attributes?.shortcode ? ' rw-flow-theme-bg' : ''} ${theme}`}>
        <Utilities/>
        {isDataFetched &&
            <Container inside={!attributes?.shortcode}>
                {step === 'vote' &&
                    <Vote flowId={id} flowData={flowData} setStep={setStep}/>}
                {step === 'review' &&
                    <ReviewWithStars flowId={id} flowData={flowData} setStep={setStep}/>}
                {step === 'public-review' &&
                    <PublicReview flowId={id} flowData={flowData}/>}
                {!attributes?.shortcode &&
                    <div>
                        <img src={generalLogo} alt="Logo"/>
                    </div>}
            </Container>}
    </div>
}
