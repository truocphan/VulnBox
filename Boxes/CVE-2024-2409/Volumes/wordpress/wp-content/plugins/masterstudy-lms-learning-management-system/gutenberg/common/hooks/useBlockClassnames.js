
const useBlockClassnames = ({ mobile, tablet, desktop }) => {
  const breakpoints = ['mobile', 'tablet', 'desktop'];
  const classNames = [];

  breakpoints.forEach((breakpoint) => {
    const current = { mobile, tablet, desktop }[breakpoint];

    const properties = [
      'display',
      'textAlign',
      'flexDirection',
      'flexWrap',
      'justifyContent',
      'alignItems',
      'position',
      'overflow',
      'bgImage'
    ];

    properties.forEach((property) => {
      const value = current?.[property];
      const shouldAddClass = value && (property !== 'display' || value !== 'block');

      if (shouldAddClass) {
        classNames.push(`${property}-${breakpoint}-${value}`);
      }
    });
  });

  return classNames;
};

export { useBlockClassnames };
