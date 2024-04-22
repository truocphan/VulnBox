import { __ } from '@wordpress/i18n';
import {
  ColorPalette,
  Panel,
  PanelBody,
  FontSizePicker,
} from '@wordpress/components';
import {
  RichText,
  useBlockProps,
  InspectorControls,
  InnerBlocks,
} from '@wordpress/block-editor';

import { FontWeightPicker } from './FontWeightPicker';
import { useBlockClientId, useBlockStyle } from '../../common/hooks';

import './style.scss';

const TEMPLATE = [['masterstudy/icon']];

export default function Edit({ attributes, setAttributes, clientId }) {
  const {
    blockColor,
    descColor,
    description,
    descFontSize,
    descFontWeight,
    title,
    titleColor,
    titleFontSize,
    titleFontWeight,
  } = attributes;

  useBlockClientId(attributes, setAttributes, clientId);

  const { blockClassName, blockStyleVariables } = useBlockStyle('iconbox', {
    titleColor,
    titleFontSize,
    titleFontWeight,
    descColor,
    descFontSize,
    descFontWeight,
  });

  const blockProps = useBlockProps({
    className: `${blockClassName} ${blockClassName}-${clientId}`,
    style: { backgroundColor: blockColor },
  });

  return (
    <>
      <div {...blockProps}>
        <div>
          <InnerBlocks template={TEMPLATE} templateLock='all' />
        </div>
        <div>
          <RichText
            tagName='h1'
            placeholder={__(
              'Title',
              'masterstudy-lms-learning-management-system',
            )}
            value={title}
            onChange={(newTitle) => setAttributes({ title: newTitle })}
            className={`${blockClassName}__title`}
            style={{
              color: titleColor,
              fontSize: titleFontSize,
              fontWeight: titleFontWeight,
            }}
          />
          <RichText
            tagName='p'
            placeholder={__(
              'Add description',
              'masterstudy-lms-learning-management-system',
            )}
            value={description}
            onChange={(newDesc) => setAttributes({ description: newDesc })}
            className={`${blockClassName}__description`}
            style={{
              color: descColor,
              fontSize: descFontSize,
              fontWeight: descFontWeight,
            }}
          />
        </div>
      </div>
      <InspectorControls>
        <Panel
          header={__(
            'General settings',
            'masterstudy-lms-learning-management-system',
          )}
        >
          <PanelBody title={__('Background', 'masterstudy-lms-learning-management-system')}>
            <ColorPalette
              label={__('Background color', 'masterstudy-lms-learning-management-system',)}
              value={blockColor}
              onChange={blockColor => setAttributes({ blockColor })}
            />
          </PanelBody>
          <PanelBody title={__('Title', 'masterstudy-lms-learning-management-system')}>
            <FontSizePicker
              __nextHasNoMarginBottom
              label={__('Font size', 'masterstudy-lms-learning-management-system')}
              fontSizes={[
                { name: 'Small', size: '14px', slug: 'small' },
                { name: 'Normal', size: '20px', slug: 'normal' },
                { name: 'Big', size: '26px', slug: 'big' },
              ]}
              units={['px', 'em', 'rem']}
              withSlider
              onChange={(value) => setAttributes({ titleFontSize: value })}
              value={titleFontSize}
            />
            <FontWeightPicker
              onChange={(titleFontWeight) => setAttributes({ titleFontWeight })}
              value={titleFontWeight}
            />
            <ColorPalette
              label={__('Color', 'masterstudy-lms-learning-management-system')}
              value={titleColor}
              onChange={(titleColor) => setAttributes({ titleColor })}
            />
          </PanelBody>
          <PanelBody
            title={__(
              'Description',
              'masterstudy-lms-learning-management-system',
            )}
          >
            <FontSizePicker
              __nextHasNoMarginBottom
              label={__(
                'Font size',
                'masterstudy-lms-learning-management-system',
              )}
              fontSizes={[
                { name: 'Small', size: '8px', slug: 'small' },
                { name: 'Normal', size: '14px', slug: 'normal' },
                { name: 'Big', size: '20px', slug: 'big' },
              ]}
              units={['px', 'em', 'rem']}
              withSlider
              onChange={(value) => setAttributes({ descFontSize: value })}
              value={descFontSize}
            />
            <FontWeightPicker
              onChange={(descFontWeight) => setAttributes({ descFontWeight })}
              value={descFontWeight}
            />
            <ColorPalette
              label={__('Color', 'masterstudy-lms-learning-management-system')}
              value={descColor}
              onChange={(descColor) => setAttributes({ descColor })}
            />
          </PanelBody>
        </Panel>
      </InspectorControls>
      <style>
        {`.${blockClassName}-${clientId} { ${blockStyleVariables} }`}
      </style>
    </>
  );
}
