import apiClient from '../utilities/api-client';

export const getUserFlows = async () => apiClient.get('/getuserflows');
export const createFlow = async (data) => apiClient.post('/tocflow', data);
export const getFlow = async (id) => apiClient.get(`/tocflow/${id}`);
export const getProgrammeTypes = async () => apiClient.get('getprogrammetypes');
export const updateFlow = async (id, data) => apiClient.put(`/tocflow/${id}`, data);
export const getTocs = async (flowId) => apiClient.get(`/tocflowtocs/${flowId}`);
export const getTocVersions = async (tocId) => apiClient.get(`/tocs/${tocId}/versions`);
export const createToc = async (flowId, type, title) =>
  apiClient.post('tocs', {
    tocFlow_id: flowId,
    toc_type: type,
    toc_title: title,
    toc: {},
  });
export const getTocData = async (tocId) => apiClient.get(`/tocs/${tocId}`);
export const updateTocEditorData = async (tocId, data) =>
  apiClient.put(`/tocs/${tocId}`, { toc: data });
export const publishToc = async (tocId, narrative, image) =>
  apiClient.post(`/tocs/${tocId}/publish`, { narrative, image });
export const getTocLevels = async () => apiClient.get('/getlevels');
export const createNewTocVersion = async (tocId, data) =>
  apiClient.post(`/tocs`, { toc_id: tocId, newVersion: true, toc: data });

// Admin actions for a flow
export const getPendingFlows = async () => apiClient.get(`/showTocFlowForApproval`);
export const acceptFlow = async (id) => apiClient.put(`/tocflow/${id}/authorize`);
export const rejectFlow = async (id, data) => apiClient.put(`/tocflow/${id}/reject`, data);
