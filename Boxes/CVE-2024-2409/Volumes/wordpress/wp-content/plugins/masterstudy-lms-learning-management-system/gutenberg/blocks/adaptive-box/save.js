import classnames from "classnames";
import { Fragment } from "@wordpress/element";
import { useBlockProps, useInnerBlocksProps } from "@wordpress/block-editor";
import { useBlockClassnames } from "../../common/hooks";

const Save = ({ attributes }) => {
  const blockClassNames = useBlockClassnames(attributes);
  const clientId = attributes.clientId ? attributes.clientId : "default";
  const blockProps = useBlockProps.save({
    className: classnames(
      `wp-block-masterstudy-adaptive-box wp-block-masterstudy-adaptive-box-${clientId} is-type-${attributes.display}`,
      blockClassNames,
    ),
    style: attributes.isBackgroundImage
      ? { backgroundImage: `url(${attributes.backgroundImage})` }
      : {},
  });

  return (
    <Fragment>
      <div {...useInnerBlocksProps.save(blockProps)} />
      <style>{attributes.blockStyles}</style>
    </Fragment>
  );
};

export default Save;
