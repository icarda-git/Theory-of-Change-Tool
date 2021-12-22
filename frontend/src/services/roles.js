/* eslint-disable import/prefer-default-export */
import apiClient from '../utilities/api-client';

export const getSystemRoles = async () => apiClient.get('/getroles');
