export interface PaginatorLinksData {
  prev?: string | null;
  self?: string | null;
  next?: string | null;
}

export interface PaginatorMetaData {
  current_page?: number | null;
  per_page?: number | null;
  total_rows?: number | null;
}
