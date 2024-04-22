import { __ } from "@wordpress/i18n";
import {
  PanelRow,
  Flex,
  FlexItem,
  GradientPicker,
} from "@wordpress/components";
import { Fragment } from "@wordpress/element";

const Gradient = ({ values, onChangeCssProperty }) => {
  return (
    <Fragment>
      <PanelRow>
        <Flex>
          <FlexItem style={{ width: "100%" }}>
            <h4 style={{ marginBottom: "-24px" }}>
              {__("Gradient", "masterstudy-lms-learning-management-system")}
            </h4>
          </FlexItem>
        </Flex>
      </PanelRow>
      <PanelRow>
        <GradientPicker
          gradients={[]}
          value={values?.backgroundImage}
          onChange={(value) =>
            onChangeCssProperty({
              type: "string",
              cssProperty: "backgroundImage",
              value,
            })
          }
        />
      </PanelRow>
    </Fragment>
  );
};
export default Gradient;
