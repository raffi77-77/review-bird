/**
 * Use this file for JavaScript code that you want to run in the front-end 
 * on posts/pages that contain this block.
 *
 * When this file is defined as the value of the `viewScript` property
 * in `block.json` it will be enqueued on the front end of the site.
 *
 * Example:
 *
 * ```js
 * {
 *   "viewScript": "file:./view.js"
 * }
 * ```
 *
 * If you're not making any changes to this file because your project doesn't need any 
 * JavaScript running in the front-end, then you should delete this file and remove 
 * the `viewScript` property from `block.json`. 
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#view-script
 */

import {createRoot} from '@wordpress/element';
import Flow from "./components/flow/flow";

window.addEventListener('DOMContentLoaded', () => {
    const flows = document.querySelectorAll('.review-bird-flow');
    if (flows) {
        flows.forEach(flowEl => {
            const flowID = flowEl.getAttribute('data-flow_id'),
                flowAttributes = JSON.parse(flowEl.getAttribute('data-flow_attributes'));
            // Remove attributes
            flowEl.removeAttribute('data-flow_id');
            flowEl.removeAttribute('data-flow_attributes');
            // Render chatbot
            const root = createRoot(flowEl);
            root.render(<Flow id={flowID} attributes={flowAttributes}/>);
        });
    }
});
