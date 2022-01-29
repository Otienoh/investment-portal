import { EntityName } from "../../ts/types/other/view.types";

export const setViewEntity = (data: any, entity: EntityName) => ({
    type: "SET_VIEW_ENTITY",
    data,
    entity,
});
export const createEntityComment = (
    entityId: number,
    entityType: EntityName,
    text: string
) => ({
    type: "CREATE_ENTITY_COMMENT",
    entityId,
    entityType,
    text,
});

export const setUserIdeaRating = (score: number) => ({
    type: "SET_USER_IDEA_RATING",
    score,
});
export const fetchUserIdeaRating = (ideaId: number) => ({
    type: "FETCH_USER_IDEA_RATING",
    ideaId,
});
