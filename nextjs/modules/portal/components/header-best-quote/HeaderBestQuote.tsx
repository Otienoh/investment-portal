import React from "react";
import { PaperWrapper } from "../../../../components/simple/paper-wrapper/PaperWrapper";
import { DtoQuoteItem } from "../../../../ts/types/response/response.types";
import { Grid, Stack } from "@mui/material";
import { ArrowUpOutlined, ArrowDownOutlined } from "@ant-design/icons";
import * as classnames from "classnames";
import classes from "../../Portal.module.scss";
interface HeaderBestQuote {
    items: DtoQuoteItem[];
}
export const HeaderBestQuote: React.FC<HeaderBestQuote> = ({ items }) => {
    // TODO: переделать рендер на стороне клиента
    const getClassByChange = (change: number) => {
        return change > 0 ? classes.changePositive : classes.changeNegative;
    };
    return (
        <PaperWrapper className="pb-2 mb-3">
            <Grid
                alignItems="center"
                justifyContent="center"
                px={2}
                container
                direction="row"
                spacing={1}
            >
                {items.map((quote) => (
                    <Grid
                        lg={2}
                        xl={2}
                        md={3}
                        sm={4}
                        xs={6}
                        container
                        direction="column"
                        alignItems="center"
                        item
                    >
                        <span className={classes.quoteHeaderName}>
                            {quote.name}
                        </span>
                        <Stack
                            className={classes.wrapperQuoteInfo}
                            alignItems="center"
                            direction="row"
                        >
                            <div
                                className={classnames(
                                    getClassByChange(
                                        quote.percent_change_today
                                    ),
                                    classes.headerIconChange
                                )}
                            >
                                <ArrowUpOutlined />
                            </div>
                            <span className={classes.quoteLastPrice}>
                                {quote.last_price}$
                            </span>
                            <span
                                className={classnames(
                                    classes.quoteChangePercent,
                                    getClassByChange(quote.percent_change_today)
                                )}
                            >
                                {`(${quote.percent_change_today.toFixed(2)}%)`}
                            </span>
                        </Stack>
                    </Grid>
                ))}
            </Grid>
        </PaperWrapper>
    );
};
