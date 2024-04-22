import { RichText, InnerBlocks, useBlockProps } from '@wordpress/block-editor';

import { useBlockStyle } from '../../common/hooks';

export default function Save({ attributes, clientId }) {
  const {
    title,
    description,
    blockColor,
    titleColor,
    titleFontSize,
    titleFontWeight,
    descColor,
    descFontSize,
    descFontWeight,
  } = attributes;

  const { blockClassName, blockStyleVariables } = useBlockStyle('iconbox');

  const blockProps = useBlockProps.save({
    className: `${blockClassName} ${blockClassName}-${clientId}`,
    style: { backgroundColor: blockColor },
  });

  return (
    <div {...blockProps}>
      <div>
        <InnerBlocks.Content />
      </div>
      <div>
        <RichText.Content
          tagName='h2'
          value={title}
          className={`${blockClassName}__title`}
          style={{
            color: titleColor,
            fontSize: titleFontSize,
            fontWeight: titleFontWeight,
          }}
        />
        <RichText.Content
          tagName='p'
          value={description}
          className={`${blockClassName}__description`}
          style={{
            color: descColor,
            fontSize: descFontSize,
            fontWeight: descFontWeight,
          }}
        />
      </div>
      <style>
        {`.${blockClassName}-${clientId} { ${blockStyleVariables} }`}
      </style>
    </div>
  );
}
