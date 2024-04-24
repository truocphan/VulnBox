import { Box, Grid, Input } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useForm } from 'react-hook-form';
import { useQuery } from 'react-query';
import { useSearchParams } from 'react-router-dom';
import { useOnType } from 'use-ontype';
import AsyncSelect from '../../../../../../../assets/js/back-end/components/common/AsyncSelect';
import { reactSelectStyles } from '../../../../../../../assets/js/back-end/config/styles';
import urls from '../../../../../../../assets/js/back-end/constants/urls';
import { CoursesResponse } from '../../../../../../../assets/js/back-end/types/course';
import { UsersApiResponse } from '../../../../../../../assets/js/back-end/types/users';
import API from '../../../../../../../assets/js/back-end/utils/api';
import {
	deepClean,
	deepMerge,
} from '../../../../../../../assets/js/back-end/utils/utils';

interface FilterParams {
	course?: string | number;
	user?: string | number;
	search?: string;
}

interface Props {
	setFilterParams: any;
	filterParams: FilterParams;
}

const AnnouncementFilter: React.FC<Props> = ({
	filterParams,
	setFilterParams,
}) => {
	const courseAPI = new API(urls.courses);
	const userAPI = new API(urls.users);
	const [searchParams] = useSearchParams();
	const announcementStatus = searchParams.get('status') || 'any';

	const courseQueries = useQuery<CoursesResponse>('courseList', () =>
		courseAPI.list({
			order_by: 'name',
			order: 'asc',
			per_page: 5,
		})
	);

	const userQueries = useQuery<UsersApiResponse>('userList', () =>
		userAPI.list({
			order_by: 'name',
			order: 'asc',
			per_page: 5,
		})
	);

	const { handleSubmit, register, setValue } = useForm();

	const onSearchInput = useOnType(
		{
			onTypeFinish: (val: string) => {
				setFilterParams({
					parent: 0,
					user: filterParams.user,
					course: filterParams.course,
					search: val,
					status: announcementStatus,
				});
			},
		},
		800
	);

	const onChange = (data: FilterParams) => {
		setFilterParams(
			deepClean(
				deepMerge(data, {
					search: filterParams.search,
					parent: 0,
					status: announcementStatus,
				})
			)
		);
	};

	return (
		<Box px={{ base: 6, md: 12 }}>
			<form onChange={handleSubmit(onChange)}>
				<Grid gridTemplateColumns={{ md: 'repeat(3, 1fr)' }} gap="4">
					<Input
						placeholder={__('Search Announcements', 'masteriyo')}
						{...onSearchInput}
						height="40px"
					/>
					<AsyncSelect
						{...register('course_id')}
						onChange={(selectedOption: any) => {
							setValue('course_id', selectedOption?.value.toString());
							handleSubmit(onChange)();
						}}
						placeholder={__('Filter by Course', 'masteriyo')}
						isClearable={true}
						styles={reactSelectStyles}
						cacheOptions={true}
						loadingMessage={() => __('Searching course...', 'masteriyo')}
						noOptionsMessage={({ inputValue }) =>
							inputValue.length > 0
								? __('Course not found.', 'masteriyo')
								: courseQueries.isLoading
								? __('Loading...', 'masteriyo')
								: __('Please enter 1 or more characters.', 'masteriyo')
						}
						defaultOptions={
							courseQueries.isSuccess
								? courseQueries?.data?.data?.map((course) => {
										return {
											value: course.id,
											label: `(#${course.id} - ${course.name})`,
										};
								  })
								: []
						}
						loadOptions={(searchValue, callback) => {
							if (searchValue.length < 0) {
								return callback([]);
							}
							courseAPI.list({ search: searchValue }).then((data) => {
								callback(
									data?.data?.map((course: any) => {
										return {
											value: course.id,
											label: `#${course.id} ${course.name}`,
										};
									})
								);
							});
						}}
					/>

					<AsyncSelect
						{...register('author_id')}
						onChange={(selectedOption: any) => {
							setValue('author_id', selectedOption?.value.toString());
							handleSubmit(onChange)();
						}}
						placeholder={__('Filter by Author', 'masteriyo')}
						isClearable={true}
						styles={reactSelectStyles}
						cacheOptions={true}
						loadingMessage={() => __('Searching author...', 'masteriyo')}
						noOptionsMessage={({ inputValue }) =>
							inputValue.length > 0
								? __('Author not found.', 'masteriyo')
								: userQueries.isLoading
								? __('Loading...', 'masteriyo')
								: __('Please enter 1 or more characters.', 'masteriyo')
						}
						defaultOptions={
							userQueries.isSuccess
								? userQueries?.data?.data?.map((author) => {
										return {
											value: author.id,
											label: `${author.username} (#${author.id} - ${author.email})`,
										};
								  })
								: []
						}
						loadOptions={(searchValue, callback) => {
							if (searchValue.length < 0) {
								return callback([]);
							}
							userAPI.list({ search: searchValue }).then((data) => {
								callback(
									data?.data?.map((author: any) => {
										return {
											value: author.id,
											label: `${author.username} (#${author.id} - ${author.email})`,
										};
									})
								);
							});
						}}
					/>
				</Grid>
			</form>
		</Box>
	);
};

export default AnnouncementFilter;
