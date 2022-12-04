import Link from 'next/link';
import { useEffect, useState } from 'react';
import classes from './ResponseModal.module.css';

const Modal = (props) => {
  const [copy, setCopy] = useState('Copy Url');

  return (
    <>
      <div
        className={classes.backdrop}
        onClick={() => {
          props.toggleResponse();
        }}
      />
      <div className={classes.modal}>
        <div className={classes.header}>
          <h2>JSON Response</h2>
          <pre>{props.resp && JSON.stringify(props.resp, null, 2)}</pre>
        </div>
        {props.error && (
          <div className={classes.content}>
            <h2>Errors</h2>
            {props.error.split('\n').map((str) => (
              <p key={str}>{str}</p>
            ))}
          </div>
        )}
        {props.url && (
          <footer className={classes.actions}>
            <Link href={props.url}>{props.url}</Link>
            <button
              className={classes.button}
              onClick={() => {
                navigator.clipboard.writeText(props.url);
                setCopy('Copied!');
              }}
            >
              {copy}
            </button>
          </footer>
        )}
      </div>
    </>
  );
};
export default Modal;
