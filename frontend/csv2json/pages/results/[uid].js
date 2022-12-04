import { useEffect, useState } from 'react';
import Page from '../../components/Page';
import Portals from '../../components/Portal';
import ResponseModal from '../../components/Modals/ResponseModal';
import CSVForm from '../../components/Form';
import { useRouter } from 'next/router';
export default function Uid() {
  const router = useRouter();
  const path = router.asPath.split('/')?.slice(-1)?.join('');
  const [resp, setResp] = useState('');
  const [error, setError] = useState('');
  const [content, setContent] = useState('');
  useEffect(() => {
    const json = JSON.parse(localStorage.getItem(path));
    setResp(json?.resp);
    setError(json?.error);
    setContent(json?.content);
  }, [path]);
  const onSubmitHandler = (resp, error) => {
    setResp(resp);
    setError(error);
    localStorage.setItem(pageId, JSON.stringify({ resp, error, content }));
  };
  return (
    <Page title={`Results | ${path}`}>
      <div>
        <CSVForm content={content} onSubmitForm={onSubmitHandler} />
        {resp && (
          <Portals>
            <ResponseModal
              error={error}
              resp={resp}
              toggleResponse={() => {
                setResp('');
              }}
            />
          </Portals>
        )}
      </div>
    </Page>
  );
}
