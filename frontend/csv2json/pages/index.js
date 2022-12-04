import { useState } from 'react';
import CSVForm from '../components/Form';
import ResponseModal from '../components/Modals/ResponseModal';
import Page from '../components/Page';
import Portals from '../components/Portal';
import nextId from 'react-id-generator';
import BaseUrl from '../lib/utils/url';
export default function Home() {
  const [resp, setResp] = useState('');
  const [error, setError] = useState('');
  const [content, setContent] = useState('');
  const [url, setUrl] = useState('');
  const onSubmitHandler = (resp, error, content) => {
    setResp(resp);
    setError(error);
    setContent(content);
    const pageId = nextId();
    setUrl(BaseUrl + pageId);
    localStorage.setItem(pageId, JSON.stringify({ resp, error, content }));
  };
  return (
    <Page title="Home">
      <div>
        <CSVForm onSubmitForm={onSubmitHandler} />
        {resp && (
          <Portals>
            <ResponseModal
              error={error}
              resp={resp}
              toggleResponse={() => {
                setResp('');
              }}
              url={url}
            />
          </Portals>
        )}
      </div>
    </Page>
  );
}
