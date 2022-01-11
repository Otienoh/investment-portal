import React, { Fragment } from "react";
import { useSelector } from "react-redux";
import { StoreData } from "../../../ts/types/redux/store.types";
import { LoadArticleItems } from "./load-article-items/LoadArticleItems";
import { ArticleItem } from "./article-item/ArticleItem";
import { Grid } from "@mui/material";

export const ArticleList: React.FC = () => {
    const { popular, simple } = useSelector(
        (state: StoreData) => state.main.articles
    );
    return (
        <Fragment>
            <Grid justifyContent="center" container direction="row" spacing={3}>
                {popular ? (
                    popular.map((article) => (
                        <Grid key={article.articleId} xs={12} item sm={4}>
                            <ArticleItem item={article} />
                        </Grid>
                    ))
                ) : (
                    <LoadArticleItems count={3} height={306} sm={4} />
                )}
            </Grid>
            <Grid
                justifyContent="center"
                mt={2}
                container
                direction="row"
                spacing={3}
            >
                {simple ? (
                    simple.map((article) => (
                        <Grid
                            key={article.articleId}
                            xs={6}
                            md={4}
                            lg={4}
                            xl={3}
                            item
                            sm={6}
                        >
                            <ArticleItem item={article} />
                        </Grid>
                    ))
                ) : (
                    <LoadArticleItems sm={3} count={8} height={206} />
                )}
            </Grid>
        </Fragment>
    );
};