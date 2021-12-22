import { Button } from 'primereact/button';
import { Dropdown } from 'primereact/dropdown';
import { InputText } from 'primereact/inputtext';
import React, { useContext, useEffect, useRef, useState } from 'react';
import { getSystemRoles } from '../../services/roles';
import { ToastContext, UserContext } from '../../store';

const TeamCompositionList = ({ items, setItems }) => {
  const isCancelled = useRef(false);
  const [roles, setRoles] = useState([]);
  const { setError } = useContext(ToastContext);
  const { profile } = useContext(UserContext);

  const loadRoles = async () => {
    try {
      const { data } = await getSystemRoles();
      if (!isCancelled.current) {
        setRoles(data.map(({ name: label, id: value }) => ({ label, value })));
      }
    } catch (error) {
      setError(error.message);
    }
  };

  useEffect(() => {
    loadRoles();
    return () => {
      isCancelled.current = true;
    };
  }, []); // eslint-disable-line

  const removeItem = (id) => {
    setItems(items.filter((item) => item.id !== id));
  };

  const updateItem = (id, props) => {
    setItems(items.map((item) => (item.id === id ? { ...item, ...props } : item)));
  };

  return (
    <>
      <div className="p-mb-3">
        {items.map((item) => (
          <div key={item.id} className="p-grid p-formgrid p-d-flex p-ai-center p-mb-2">
            <div className="p-col-6">
              <InputText
                type="text"
                disabled={profile?.email === item.email}
                value={item?.email || ''}
                placeholder="Email address"
                onChange={(e) => updateItem(item.id, { email: e.target.value })}
              />
            </div>
            <div className="p-col-5">
              {profile?.email === item.email ? (
                <span>Leader</span>
              ) : (
                <Dropdown
                  value={item?.role || ''}
                  options={roles}
                  onChange={(e) => updateItem(item.id, { role: e.value })}
                />
              )}
            </div>
            <div className="p-col-1">
              {profile?.email !== item.email && (
                <Button
                  onClick={() => removeItem(item.id)}
                  icon="pi pi-trash"
                  className="p-button-danger p-button-sm"
                />
              )}
            </div>
          </div>
        ))}
      </div>
    </>
  );
};

export default TeamCompositionList;
