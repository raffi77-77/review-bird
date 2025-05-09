import domReady from '@wordpress/dom-ready';
import {createRoot} from '@wordpress/element';
import PositiveReviewResponse from './posts/components/postbox/positive-review-response';

domReady(() => {
    const container = document.querySelector('#positive-review-response .inside');
    if (!container) {
        // No container found to initialize
        return;
    }
    const root = createRoot(container);

    root.render(<PositiveReviewResponse/>);
});