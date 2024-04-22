import { __ } from "@wordpress/i18n";
import FontAwesomeIconList from "./icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  Panel,
  PanelBody,
  PanelRow,
  __experimentalUnitControl as UnitControl,
  __experimentalHStack as HStack,
} from "@wordpress/components";
import { useBlockClientId, useBlockStyle } from "../../common/hooks";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { useEffect, useState, Fragment } from "@wordpress/element";
import {
  DevicesTabControl,
  setDeviceAttribute,
  getDeviceAttributes,
} from "../../common/devices";

const Edit = ({ attributes, setAttributes, clientId }) => {
  const { iconName, iconAttr, blockStyles } = attributes;
  const [device, setDevice] = useState("desktop");
  // clientId.
  useBlockClientId(attributes, setAttributes, clientId);
  const blockAttributes = getDeviceAttributes(device, attributes, [
    {
      cssProperty: "width",
      default: "24px",
    },
    {
      cssProperty: "height",
      default: "24px",
    },
  ]);

  const { blockClassName, accumulatedStyles } = useBlockStyle(
    "icon",
    blockAttributes,
    device,
    attributes.blockStyles,
  );
  
  const blockProps = useBlockProps({
    className: `${blockClassName} ${blockClassName}-${attributes.clientId}`,
    style: blockStyles,
  });

  useEffect(() => {
    setAttributes({ blockStyles: accumulatedStyles });
  }, [accumulatedStyles]);

  return (
    <Fragment>
      <InspectorControls>
        <Panel className="masterstudy-components-panel">
          <div style={{ borderBottom: "1px solid #e0e0e0" }} class="masterstudy-icon__editor">
            <PanelBody
              title={__("Icons", "masterstudy-lms-learning-management-system")}
            >
              <DevicesTabControl device={device} setDevice={setDevice} />
              <HStack align="baseline" spacing="3">
                <UnitControl
                  label={__(
                    "Width",
                    "masterstudy-lms-learning-management-system",
                  )}
                  onChange={(width) => {
                    setDeviceAttribute(device, attributes, setAttributes, {
                      type: "string",
                      cssProperty: "width",
                      value: width,
                    })
                  }}
                  value={blockAttributes.width}
                />
                <UnitControl
                  label={__(
                    "Height",
                    "masterstudy-lms-learning-management-system",
                  )}
                  onChange={(height) => {
                    setDeviceAttribute(device, attributes, setAttributes, {
                      type: "string",
                      cssProperty: "height",
                      value: height,
                    })
                  }}
                  value={blockAttributes.height}
                />
              </HStack>
              <PanelRow>
                <div className="masterstudy-icon__view">
                  <FontAwesomeIcon icon={iconAttr} />
                </div>
              </PanelRow>
              <FontAwesomeIconList
                selectedIcon={iconName}
                setAttributes={(iconName, iconAttr) =>
                  setAttributes({ iconName, iconAttr })
                }
              />
            </PanelBody>
          </div>
        </Panel>
      </InspectorControls>
      <div {...blockProps}>
        <span>
          <FontAwesomeIcon icon={iconAttr} fill="currentColor" />
        </span>
      </div>
    </Fragment>
  );
};

export default Edit;
