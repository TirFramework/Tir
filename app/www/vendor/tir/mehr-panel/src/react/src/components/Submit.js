import { Button } from "antd";
import { Link, useParams } from "react-router-dom";
import { useLanguage } from "../context/LanguageContext";
import { useEffect, useState } from "react";

const Submit = (props) => {
  const { pageModule } = useParams();
  const { t } = useLanguage();
  const [clicked, setClicked] = useState(false);

  useEffect(() => {
    if (props.loading === false) {
      setClicked(false);
    }
  }, [props.loading]);

  return (
    <Button
      type="primary"
      loading={props.loading && clicked}
      disabled={clicked ? false : props.loading}
      onClick={() => {
        setClicked(true);

        props.form.redirect = false;
        props.form.submit();
      }}
    >
      <>
        {props.display ? (
          props.display
        ) : (
          <>{props.pageId ? t.UPDATE : t.CREATE}</>
        )}
      </>
    </Button>
  );
};

export default Submit;
