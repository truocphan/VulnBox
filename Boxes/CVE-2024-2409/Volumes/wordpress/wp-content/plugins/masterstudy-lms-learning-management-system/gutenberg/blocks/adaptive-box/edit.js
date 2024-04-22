import { __ } from "@wordpress/i18n";
import classnames from "classnames";
import { useEffect, useState, Fragment } from "@wordpress/element";
import {
  Panel,
  PanelBody,
  PanelRow,
  ToggleControl,
  ToolbarButton,
  __experimentalRadio as Radio,
  __experimentalRadioGroup as RadioGroup,
  __experimentalToggleGroupControl as ToggleGroupControl,
  __experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from "@wordpress/components";
import {
  InnerBlocks,
  useBlockProps,
  InspectorControls,
  useInnerBlocksProps,
  store as blockEditorStore,
  MediaUpload,
} from "@wordpress/block-editor";

import { useSelect } from "@wordpress/data";
import Devices from "./devices/Devices";
import { useBlockClassnames, useAdaptiveBlockStyles, useBlockClientId } from "../../common/hooks";
import "./editor.scss";

const Edit = (props) => {
  const { attributes, setAttributes, clientId } = props;
  const { isBackgroundImage, backgroundImage } = attributes;
  const [device, setDevice] = useState("desktop");

  useBlockClientId(attributes, setAttributes, clientId)

  const { hasInnerBlocks } = useSelect(
    (select) => {
      const { getBlock } = select(blockEditorStore);
      const block = getBlock(clientId);

      return {
        hasInnerBlocks: !!(block && block.innerBlocks.length),
      };
    },
    [clientId],
  );

  const blockStyles = useAdaptiveBlockStyles({
    blockName: "adaptive-box",
    clientId,
    properties: {
      desktop: attributes["desktop"],
      tablet: attributes["tablet"],
      mobile: attributes["mobile"],
    },
  });

  useEffect(() => {
    setAttributes({ blockStyles });
  }, [blockStyles]);

  const blockClassNames = useBlockClassnames(attributes);
  const blockProps = useBlockProps({
    className: classnames(
      `wp-block-masterstudy-adaptive-box wp-block-masterstudy-adaptive-box-${clientId} is-type-${attributes.display}`,
      blockClassNames,
    ),
    style: isBackgroundImage
      ? { backgroundImage: `url(${backgroundImage})` }
      : {},
  });

  const innerBlocksProps = useInnerBlocksProps(blockProps, {
    renderAppender: hasInnerBlocks
      ? undefined
      : InnerBlocks.ButtonBlockAppender,
  });

  const onImageSelect = (image) => {
    setAttributes({ backgroundImage: image.url ? image.url : "" });
  };

  const removeSlideImage = () => {
    setAttributes({ backgroundImage: "" });
  };

  return (
    <Fragment>
      <InspectorControls>
        <Panel className="masterstudy-components-panel">
          <div style={{ borderTop: "1px solid #e0e0e0" }}>
            <PanelBody
              title={__(
                "Adaptive settings",
                "masterstudy-lms-learning-management-system",
              )}
            >
              <RadioGroup
                onChange={(display) => setAttributes({ display })}
                checked={attributes.display}
              >
                <Radio value="flex">
                  {__(
                    "Adaptive flex",
                    "masterstudy-lms-learning-management-system",
                  )}
                </Radio>
                <Radio value="block">
                  {__(
                    "Adaptive block",
                    "masterstudy-lms-learning-management-system",
                  )}
                </Radio>
              </RadioGroup>

              <ToggleControl
                label={__(
                  "Background image",
                  "masterstudy-lms-learning-management-system",
                )}
                checked={isBackgroundImage}
                onChange={(isBackgroundImage) =>
                  setAttributes({ isBackgroundImage })
                }
              />
              {isBackgroundImage && (
                <MediaUpload
                  onSelect={onImageSelect}
                  allowedTypes={["image"]}
                  render={({ open }) => (
                    <PanelRow>
                      <ToolbarButton onClick={open} className="is-primary">
                        {!backgroundImage && (
                          <span>
                            {__(
                              "Add image",
                              "masterstudy-lms-learning-management-system",
                            )}
                          </span>
                        )}
                        {backgroundImage && (
                          <span>
                            {__(
                              "Change image",
                              "masterstudy-lms-learning-management-system",
                            )}
                          </span>
                        )}
                      </ToolbarButton>
                      {backgroundImage && (
                        <ToolbarButton
                          onClick={removeSlideImage}
                          className="is-destructive is-primary"
                        >
                          <span>
                            {__(
                              "Remove image",
                              "masterstudy-lms-learning-management-system",
                            )}
                          </span>
                        </ToolbarButton>
                      )}
                    </PanelRow>
                  )}
                />
              )}

              <ToggleGroupControl
                label={__(
                  "Select device",
                  "masterstudy-lms-learning-management-system",
                )}
                onChange={(device) => setDevice(device)}
                size="default"
                value={device}
              >
                <ToggleGroupControlOption
                  label={__(
                    "Mobile",
                    "masterstudy-lms-learning-management-system",
                  )}
                  value="mobile"
                />
                <ToggleGroupControlOption
                  label={__(
                    "Tablet",
                    "masterstudy-lms-learning-management-system",
                  )}
                  value="tablet"
                />
                <ToggleGroupControlOption
                  label="Desktop"
                  value={__(
                    "desktop",
                    "masterstudy-lms-learning-management-system",
                  )}
                />
              </ToggleGroupControl>
              <Devices
                device={device}
                attributes={attributes}
                setAttributes={setAttributes}
              />
            </PanelBody>
          </div>
        </Panel>
      </InspectorControls>

      <div {...innerBlocksProps} />
      <style>{blockStyles}</style>
    </Fragment>
  );
};

export default Edit;
