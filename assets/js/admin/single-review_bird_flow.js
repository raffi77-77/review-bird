import domReady from '@wordpress/dom-ready';
import {createRoot, StrictMode} from '@wordpress/element';
import PositiveReviewResponse from './posts/components/postbox/positive-review-response';

domReady(() => {
    const container = document.querySelector('#positive-review-response .inside');
    if (!container) {
        // No container found to initialize
        return;
    }
    const root = createRoot(container);

    root.render(
        <StrictMode>
            <PositiveReviewResponse/>
        </StrictMode>
    );
});