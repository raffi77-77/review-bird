import {useState} from "@wordpress/element";
import StepLayout from "./components/layout";

export default function PublicReview({destinations}) {
    const [destinationsSlugs, setDestinationsSlugs] = useState([
        'youtube',
        'wordpress',
        'forsquare',
        'google',
        'app',
        'audible',
        'trustpilot',
        'doctolib',
        'jameda',
    ]);

    return <StepLayout className={destinations.length > 0 ? 'rw-flow-feedback-public-review-row' : ''}>
        <div className="rw-flow-title">
            <p className="rw-flow-title-in">Hinterlassen Sie eine öffentliche Bewertung</p>
        </div>
        <div className="rw-flow-label">
            <div className="rw-flow-public-review-desc">
                {destinations.length > 0 &&
                    <p className="rw-flow-public-review-desc-in">Klicken Sie auf die jeweilige Plattform, um eine offentliche Bewertung zu hinterlassen:</p>}
                {!destinations.length &&
                    <p className="rw-flow-public-review-desc-in">Mit Klick auf den unten stehenden Button können Sie eine öffentliche Bewertung abgeben:</p>}
            </div>
        </div>
        {destinations.length > 0 &&
            <div className="rw-flow-feedback-actions rw-flow-platform-slide">
                <div className="rw-flow-feedback-actions-in rw-flow-platform-slide-in">
                    <button className="rw-flow-button rw-flow-button-platform">
                        <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-'/>
                        </svg>
                    </button>
                    <button className="rw-flow-button rw-flow-button-platform">
                        <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-'/>
                        </svg>
                    </button>
                    <button className="rw-flow-button rw-flow-button-platform">
                        <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-'/>
                        </svg>
                    </button>
                    <button className="rw-flow-button rw-flow-button-platform">
                        <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow--play'/>
                        </svg>
                    </button>
                    <button className="rw-flow-button rw-flow-button-platform app-store">
                        <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow--store'/>
                        </svg>
                    </button>
                    <button className="rw-flow-button rw-flow-button-platform">
                        <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-'/>
                        </svg>
                    </button>
                    <button className="rw-flow-button rw-flow-button-platform">
                        <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-'/>
                        </svg>
                    </button>
                    <button className="rw-flow-button rw-flow-button-platform">
                        <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-'/>
                        </svg>
                    </button>
                    <button className="rw-flow-button rw-flow-button-platform">
                        <svg className='rw-flow-button-platform-i' xmlns='http://www.w3.org/2000/svg'
                             fill='none' viewBox='0 0 24 24'>
                            <use href='#rw-flow-'/>
                        </svg>
                    </button>
                </div>
            </div>}
        {!destinations.length &&
            <div className="rw-flow-feedback-actions">
                <div className="rw-flow-feedback-actions-in">
                    <button className='rw-flow-button rw-flow-button-minimal rw-flow-button-primary'>
                        <span className='rw-flow-button-desc'>Ja, öffentlich posten</span>
                    </button>
                </div>
            </div>}
    </StepLayout>
}