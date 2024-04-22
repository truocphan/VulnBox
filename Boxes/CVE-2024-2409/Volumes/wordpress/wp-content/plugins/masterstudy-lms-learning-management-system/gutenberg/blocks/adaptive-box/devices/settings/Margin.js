import { __ } from "@wordpress/i18n";
import {
  PanelRow,
  Flex,
  FlexItem,
  ToggleControl,
  __experimentalUnitControl as UnitControl,
} from "@wordpress/components";
import { Fragment } from "@wordpress/element";

const Margin = ({ values, onChangeCssProperty }) => {
  return (
    <Fragment>
      <PanelRow>
        <Flex>
          <FlexItem style={{ width: "100%" }}>
            {__("Margin", "masterstudy-lms-learning-management-system")}
          </FlexItem>
        </Flex>
      </PanelRow>
      <PanelRow>
        <Flex>
          <FlexItem style={{ width: "50%" }}>
            <UnitControl
              label="Top"
              onChange={(value) =>
                onChangeCssProperty({
                  type: "object",
                  cssProperty: "margin",
                  value: { top: value },
                })
              }
              value={
                values?.margin?.top && values.margin.top === "auto"
                  ? undefined
                  : values?.margin?.top
              }
              disabled={!!values?.margin?.top && values.margin.top === "auto"}
            />
          </FlexItem>
          <FlexItem style={{ width: "50%", paddingTop: "24px" }}>
            <ToggleControl
              label="Auto"
              checked={!!values?.margin?.top && values.margin.top === "auto"}
              onChange={(e) =>
                onChangeCssProperty({
                  type: "object",
                  cssProperty: "margin",
                  value: { top: e ? "auto" : undefined },
                })
              }
            />
          </FlexItem>
        </Flex>
      </PanelRow>
      <PanelRow>
        <Flex>
          <FlexItem style={{ width: "50%" }}>
            <UnitControl
              label="Right"
              onChange={(value) =>
                onChangeCssProperty({
                  type: "object",
                  cssProperty: "margin",
                  value: { right: value },
                })
              }
              value={
                values?.margin?.right && values.margin.right === "auto"
                  ? undefined
                  : values?.margin?.right
              }
              disabled={
                !!values?.margin?.right && values.margin.right === "auto"
              }
            />
          </FlexItem>
          <FlexItem style={{ width: "50%", paddingTop: "24px" }}>
            <ToggleControl
              label="Auto"
              checked={
                !!values?.margin?.right && values.margin.right === "auto"
              }
              onChange={(e) =>
                onChangeCssProperty({
                  type: "object",
                  cssProperty: "margin",
                  value: { right: e ? "auto" : undefined },
                })
              }
            />
          </FlexItem>
        </Flex>
      </PanelRow>
      <PanelRow>
        <Flex>
          <FlexItem style={{ width: "50%" }}>
            <UnitControl
              label="Bottom"
              onChange={(value) =>
                onChangeCssProperty({
                  type: "object",
                  cssProperty: "margin",
                  value: { bottom: value },
                })
              }
              value={
                values?.margin?.bottom && values.margin.bottom === "auto"
                  ? undefined
                  : values?.margin?.bottom
              }
              disabled={
                !!values?.margin?.bottom && values.margin.bottom === "auto"
              }
            />
          </FlexItem>
          <FlexItem style={{ width: "50%", paddingTop: "24px" }}>
            <ToggleControl
              label="Auto"
              checked={
                !!values?.margin?.bottom && values.margin.bottom === "auto"
              }
              onChange={(e) =>
                onChangeCssProperty({
                  type: "object",
                  cssProperty: "margin",
                  value: { bottom: e ? "auto" : undefined },
                })
              }
            />
          </FlexItem>
        </Flex>
      </PanelRow>
      <PanelRow>
        <Flex>
          <FlexItem style={{ width: "50%" }}>
            <UnitControl
              label="Left"
              onChange={(value) =>
                onChangeCssProperty({
                  type: "object",
                  cssProperty: "margin",
                  value: { left: value },
                })
              }
              value={
                values?.margin?.left && values.margin.left === "auto"
                  ? undefined
                  : values?.margin?.left
              }
              disabled={!!values?.margin?.left && values.margin.left === "auto"}
            />
          </FlexItem>
          <FlexItem style={{ width: "50%", paddingTop: "24px" }}>
            <ToggleControl
              label="Auto"
              checked={!!values?.margin?.left && values.margin.left === "auto"}
              onChange={(e) =>
                onChangeCssProperty({
                  type: "object",
                  cssProperty: "margin",
                  value: { left: e ? "auto" : undefined },
                })
              }
            />
          </FlexItem>
        </Flex>
      </PanelRow>
    </Fragment>
  );
};

export default Margin;
