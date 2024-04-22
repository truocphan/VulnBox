import { DECLARATION_WITH_DIRECTION, ADAPTIVE_FLEX_STYLE } from '../constants';
import { convertDirectionToString } from '../utils';

const upperToHyphenLower = (match) => '-' + match.toLowerCase();

const generateCssString = (propertiesDevice) => {
    const styles = [];

    for (let propertyName in propertiesDevice) {
        if ( DECLARATION_WITH_DIRECTION.includes(propertyName) ) {
            const propertyValueDirection = propertiesDevice[propertyName];
            const propertyValueString = convertDirectionToString(propertyValueDirection);

            if (propertyValueString !== 'undefined') {
                styles.push(
                    `${propertyName.replace(/[A-Z]/g, upperToHyphenLower)}:${propertyValueString}`
                );
            }
        } else if ( ADAPTIVE_FLEX_STYLE.includes(propertyName) && propertiesDevice[propertyName]?.trim()) {
            styles.push(
                `${propertyName.replace(/[A-Z]/g, upperToHyphenLower)}:${propertiesDevice[propertyName]} ${propertyName === 'zIndex' ? ' !important' : ''}`
            );
        }
    }

    return styles;
};

const useAdaptiveBlockStyles = ({ blockName, clientId, properties }) => {
    let output = '';
    const className = `wp-block-masterstudy-${blockName}`;
    const startClassName = `.${className}.${className}-${clientId}`;

    Object.keys(properties).forEach((device) => {
        const styles = generateCssString(properties[device]);
        if (styles.length) {
            if( 'desktop' === device ) {
                output += `${startClassName} { ${styles.join(';')} }`;
            }
            if( 'tablet' === device ) {
                output += ` @media screen and (max-width: 991.98px) { ${startClassName} { ${styles.join(';')} }}`;
            }
            if( 'mobile' === device ) {
                output += ` @media screen and (max-width: 575.98px) { ${startClassName} { ${styles.join(';')} }}`;
            }
        }
    });

    return output;
};

export { useAdaptiveBlockStyles };
