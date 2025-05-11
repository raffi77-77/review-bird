import domReady from '@wordpress/dom-ready';
import {createRoot} from '@wordpress/element';
import PositiveReviewResponse from './posts/components/postbox/positive-review-response';
import NegativeReviewResponse from "./posts/components/postbox/negative-review-response";

domReady(() => {
    const positiveReviewResponse = document.querySelector('#positive-review-response .inside');
    if (positiveReviewResponse) {
        const root = createRoot(positiveReviewResponse);

        root.render(<PositiveReviewResponse/>);
    }

    const negativeReviewResponse = document.querySelector('#negative-review-response .inside');
    if (negativeReviewResponse) {
        const root = createRoot(negativeReviewResponse);

        root.render(<NegativeReviewResponse/>);
    }
});