import { InputTextarea } from 'primereact/inputtextarea';
import React, { useEffect, useState } from 'react';

const Comments = ({
  element,
  activeMetadata,
  setData,
  comments,
  setComments,
  readOnly,
  commentsField,
}) => {
  const [c, setC] = useState(
    (element && (activeMetadata?.[commentsField] || '')) || comments || '',
  );

  useEffect(() => {
    if (element) {
      // If commentsField has been set we use a custom field indicated by it
      // within the node to save and retrieve the comments.
      setC(activeMetadata?.[commentsField] || '');
    } else {
      setC(comments);
    }
  }, [activeMetadata, comments]); // eslint-disable-line

  useEffect(() => {
    if (element) {
      setData(element.id, commentsField, c);
    } else {
      setComments(c);
    }
  }, [c]); // eslint-disable-line

  return (
    <>
      <div className="p-grid">
        <div className="p-col-12">
          <InputTextarea
            name="comments"
            disabled={readOnly}
            style={{ width: '100%', resize: 'none' }}
            rows={3}
            onChange={(e) => setC(e.target.value)}
            value={c}
            autoResize
          />
        </div>
      </div>
    </>
  );
};

export default Comments;
