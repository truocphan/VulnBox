import { __ } from '@wordpress/i18n';

export const DECLARATION_WITH_DIRECTION = ['padding', 'margin', 'borderWidth', 'inset'];
export const DIRECTIONS = ['top', 'right', 'bottom', 'left'];
export const ADAPTIVE_FLEX_STYLE = [
    'gap',             // flex
    'minWidth',        // flex block position
    'maxWidth',        // flex block position
    'flexGrow',        // flex position
    'flexShrink',      // flex position
    'flexGrow',        // flex position
    'minHeight',       // flex block position
    'flexBasis',       // flex
    'backgroundImage', // flex block
    'order',           // flex position
    'zIndex',          // position
    'borderRadius',    // position
    'overflow',        // position
];
export const FONT_SIZES = [
    {
        name: __( 'Small', 'masterstudy-lms-learning-management-system' ),
        size: '1.4rem',
        slug: 'small'
    },
    {
        name: __( 'Normal', 'masterstudy-lms-learning-management-system' ),
        size: '1.6rem',
        slug: 'normal'
    },
    {
        name: __( 'Large', 'masterstudy-lms-learning-management-system' ),
        size: '2rem',
        slug: 'lg'
    },
    {
        name: __( 'Extra Large', 'masterstudy-lms-learning-management-system' ),
        size: '2.4rem',
        slug: 'xl'
    }
];

export const DEVICE_PREVIEW =  { 
    'is-desktop-preview': 'height: 100%; width: 100%; margin: 0px auto; display: flex; flex-flow: column; background: white; border-radius: 0px; border: 0px none rgb(60, 67, 74); overflow-y: visible;', 
    'is-tablet-preview' : 'height: 1024px; width: 780px; margin: 36px auto; display: flex; flex-flow: column; background: white; border-radius: 2px; border: 1px solid rgb(221, 221, 221); overflow-y: auto;', 
    'is-mobile-preview' : 'height: 768px; width: 360px; margin: 36px auto; display: flex; flex-flow: column; background: white; border-radius: 2px; border: 1px solid rgb(221, 221, 221); overflow-y: auto;'
};