import { Box, Center, Heading, Stack, ThemingProps } from '@chakra-ui/react';
import React, { ReactNode } from 'react';

interface Props {
	title: string | ReactNode;
	subtitle: string | ReactNode;
	colorScheme: ThemingProps['colorScheme'];
	icon: ReactNode;
}

const CountBox: React.FC<Props> = (props) => {
	const { title, subtitle, colorScheme, icon } = props;

	return (
		<Box borderWidth="1px" borderColor="gray.100">
			<Stack direction="row" spacing="4" p="6">
				<Stack
					direction="row"
					spacing="4"
					align="center"
					justify="space-between"
				>
					<Center
						bg={`${colorScheme}.100`}
						w="16"
						h="16"
						rounded="xl"
						color={`${colorScheme}.500`}
					>
						{icon}
					</Center>
					<Stack direction="column">
						<Heading size="sm" color="gray.800">
							{title}
						</Heading>
						<Box color={`${colorScheme}.700`} fontWeight="bold" fontSize="md">
							{subtitle}
						</Box>
					</Stack>
				</Stack>
			</Stack>
		</Box>
	);
};

export default CountBox;
